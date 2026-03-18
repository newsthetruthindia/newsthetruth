<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\JustIn;
use App\Models\SiteSetting;
use App\Models\CitizenJournalism;
use App\Http\Controllers\NotificationController;

class ApiController extends Controller
{
    /**
     * Get the latest posts.
     */
    public function latestPosts(Request $request)
    {
        $limit = $request->get('limit', 10);
        $posts = Post::where('status', 'published')
            ->where('visibility', 'public')
            ->with(['thumbnails', 'categories.cat_data'])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get the top posts.
     */
    public function topPosts(Request $request)
    {
        $limit = $request->get('limit', 6);
        $posts = Post::where('top_post', 1)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->with(['thumbnails', 'categories.cat_data'])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get posts by category slug.
     */
    public function categoryPosts($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }

        $limit = $request->get('limit', 20);
        $posts = Post::where('status', 'published')
            ->where('visibility', 'public')
            ->whereHas('categories.cat_data', function ($q) use ($category) {
                $q->where('id', $category->id);
            })
            ->with(['thumbnails', 'categories.cat_data'])
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get all categories.
     */
    public function categories()
    {
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get a single post by slug.
     */
    public function post($slug)
    {
        $post = Post::where('slug', $slug)
            ->with(['thumbnails', 'categories.cat_data', 'metas', 'user', 'tags.tag_data', 'gallery'])
            ->first();

        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    /**
     * Get basic site settings.
     */
    public function settings()
    {
        $settings = SiteSetting::pluck('description', 'key')->toArray();
        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }

    /**
     * Submit a citizen journalism report.
     */
    public function citizenReport(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'attachment_file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,mkv|max:51200',
        ]);

        $post = new \App\Models\CitizenJournalism();
        $post->user_id = 0; // Anonymous or guest
        $post->title = $request->title;
        $post->place = $request->location;
        $post->description = $request->description;
        $post->subtitle = $request->email;
        $post->credit = $request->name ?? 'Anonymous';
        $post->datetime = now();

        if ($request->hasFile('attachment_file')) {
            $file = $request->file('attachment_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'public/uploads/citizen';

            if (!file_exists(base_path($path))) {
                mkdir(base_path($path), 0755, true);
            }

            $file->move(base_path($path), $filename);
            $post->attachment_url = url($path . '/' . $filename);
        }

        $post->save();

        // Trigger notification
        try {
            $notification = new NotificationController();
            $notification->description('New API Citizen Report: ' . $post->title);
            $notification->type('users');
            $notification->send();
        } catch (\Exception $e) {
            // Silently fail notification if it errors
        }

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully. Thank you for sharing the truth.',
            'data' => $post
        ]);
    }

    /**
     * Get all tags.
     */
    public function tags()
    {
        $tags = \App\Models\Tag::all();
        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }

    /**
     * Get all videos.
     */
    public function videos()
    {
        $videos = \App\Models\Video::orderBy('sort_order', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $videos
        ]);
    }
}
