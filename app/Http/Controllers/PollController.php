<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;

class PollController extends Controller
{
    /**
     * Get a poll with its options and vote counts.
     */
    public function getPoll($id)
    {
        $poll = Poll::with('options')->findOrFail($id);

        // Add vote counts to options
        $options = $poll->options->map(function ($option) {
            $option->vote_count = $option->votes()->count();
            return $option;
        });

        $poll->setRelation('options', $options);
        $poll->total_votes = $poll->votes()->count();

        return response()->json([
            'success' => true,
            'data' => $poll
        ]);
    }

    /**
     * Vote on a poll.
     */
    public function vote(Request $request, $id)
    {
        $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id'
        ]);

        $poll = Poll::findOrFail($id);

        if (!$poll->is_active) {
            return response()->json(['success' => false, 'message' => 'Poll is not active'], 400);
        }

        $userId = $request->user()->id;

        // Check if already voted
        $existingVote = PollVote::where('poll_id', $id)->where('user_id', $userId)->first();
        if ($existingVote) {
            return response()->json(['success' => false, 'message' => 'You have already voted on this poll'], 400);
        }

        // Verify option belongs to this poll
        $option = PollOption::where('id', $request->poll_option_id)->where('poll_id', $id)->first();
        if (!$option) {
            return response()->json(['success' => false, 'message' => 'Invalid poll option'], 400);
        }

        PollVote::create([
            'poll_id' => $id,
            'poll_option_id' => $request->poll_option_id,
            'user_id' => $userId
        ]);

        return response()->json(['success' => true, 'message' => 'Vote cast successfully']);
    }
}
