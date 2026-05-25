<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Reaction;

class InteractionController extends Controller
{
    public function getComments($postId)
    {
        $comments = Comment::where('post_id', $postId)
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->with(['user', 'replies' => function($q) {
                $q->where('is_approved', true);
            }])
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    public function addComment(Request $request, $postId)
    {
        $request->validate([
            'body' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => $request->user()->id,
            'parent_id' => $request->parent_id,
            'body' => $request->body,
            'is_approved' => true // Default true for now, can be changed to false for moderation
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment
        ]);
    }

    public function react(Request $request, $postId)
    {
        $request->validate([
            'type' => 'required|string|in:like,insightful,surprised'
        ]);

        $userId = $request->user()->id;

        // Toggle logic: if reaction exists of same type, remove it. If different type, update it. If none, create.
        $existing = Reaction::where('post_id', $postId)->where('user_id', $userId)->first();

        if ($existing) {
            if ($existing->type === $request->type) {
                $existing->delete();
                return response()->json(['success' => true, 'message' => 'Reaction removed']);
            } else {
                $existing->update(['type' => $request->type]);
                return response()->json(['success' => true, 'message' => 'Reaction updated']);
            }
        } else {
            Reaction::create([
                'post_id' => $postId,
                'user_id' => $userId,
                'type' => $request->type
            ]);
            return response()->json(['success' => true, 'message' => 'Reaction added']);
        }
    }
}
