<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    /**
     * GET /api/poll/active
     * Returns the single active poll with all options and vote percentages.
     */
    public function active(Request $request)
    {
        $poll = Poll::active()->with('options')->latest()->first();

        if (!$poll) {
            return response()->json(['success' => true, 'data' => null]);
        }

        $total     = $poll->totalVotes();
        $voterId   = $this->getVoterIdentifier($request);
        $hasVoted  = PollVote::whereIn('poll_option_id', $poll->options->pluck('id'))
                             ->where('voter_identifier', $voterId)
                             ->exists();
        $votedOptionId = $hasVoted
            ? PollVote::whereIn('poll_option_id', $poll->options->pluck('id'))
                      ->where('voter_identifier', $voterId)
                      ->value('poll_option_id')
            : null;

        $options = $poll->options->map(fn ($opt) => [
            'id'         => $opt->id,
            'text'       => $opt->option_text,
            'votes'      => (int) $opt->votes,
            'percentage' => $total > 0 ? round(($opt->votes / $total) * 100, 1) : 0,
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'             => $poll->id,
                'question'       => $poll->question,
                'total_votes'    => $total,
                'has_voted'      => $hasVoted,
                'voted_option_id'=> $votedOptionId,
                'expires_at'     => $poll->expires_at?->toISOString(),
                'options'        => $options,
            ],
        ]);
    }

    /**
     * POST /api/poll/vote
     * Submit a vote for a poll option.
     */
    public function vote(Request $request)
    {
        $request->validate(['option_id' => 'required|integer|exists:poll_options,id']);

        $option  = PollOption::with('poll')->findOrFail($request->option_id);
        $poll    = $option->poll;
        $voterId = $this->getVoterIdentifier($request);

        // Verify poll is still active
        if (!$poll->is_active || ($poll->expires_at && $poll->expires_at->isPast())) {
            return response()->json(['success' => false, 'message' => 'This poll is no longer active.'], 422);
        }

        // Prevent double voting
        $alreadyVoted = PollVote::whereIn('poll_option_id', $poll->options()->pluck('id'))
                                 ->where('voter_identifier', $voterId)
                                 ->exists();
        if ($alreadyVoted) {
            return response()->json(['success' => false, 'message' => 'You have already voted in this poll.'], 422);
        }

        // Atomic increment vote count + record who voted
        DB::transaction(function () use ($option, $voterId) {
            PollVote::create([
                'poll_option_id'   => $option->id,
                'voter_identifier' => $voterId,
            ]);
            $option->increment('votes');
        });

        return $this->active($request);
    }

    /**
     * Build a stable, anonymous voter identifier from IP + user agent.
     * Never stores personal data — only a hash.
     */
    private function getVoterIdentifier(Request $request): string
    {
        return hash('sha256', $request->ip() . '|' . $request->userAgent());
    }
}
