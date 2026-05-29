<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Post;
use App\Mail\DailyNewsletterMail;
use Illuminate\Support\Facades\Mail;

class SendDailyNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily top news to all subscribers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Selecting top 5 trending articles for the daily newsletter...');
        
        $topViewedPosts = collect();
        $limit = 5;
        
        $topViews = \Illuminate\Support\Facades\DB::table('post_views')
            ->select('post_id', \Illuminate\Support\Facades\DB::raw('SUM(viewer_count) as total_views'))
            ->where('created_at', '>=', now()->subHours(24))
            ->groupBy('post_id')
            ->orderByDesc('total_views')
            ->take($limit)
            ->get();
            
        $topViewIds = $topViews->pluck('post_id')->toArray();
        
        if (!empty($topViewIds)) {
            $placeholders = implode(',', array_fill(0, count($topViewIds), '?'));
            $topViewedPosts = Post::whereIn('id', $topViewIds)
                ->where('status', 'published')
                ->orderByRaw("FIELD(id, {$placeholders})", $topViewIds)
                ->get();
        }
        
        // Fallback: If not enough views recorded today, grab recent posts
        if ($topViewedPosts->count() < $limit) {
            $existingIds = $topViewedPosts->pluck('id')->toArray();
            $extraCount = $limit - $topViewedPosts->count();
            
            $fallbackPosts = Post::where('status', 'published')
                ->whereNotIn('id', $existingIds)
                ->orderBy('id', 'desc')
                ->take($extraCount)
                ->get();
                
            $topViewedPosts = $topViewedPosts->merge($fallbackPosts);
        }

        $posts = $topViewedPosts;

        if ($posts->isEmpty()) {
            $this->info('No recent posts to send.');
            return 0;
        }

        $users = User::where('type', 'user')->get();
        
        $this->info('Sending newsletters to ' . $users->count() . ' users...');
        
        $count = 0;
        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new DailyNewsletterMail($user, $posts));
                $count++;
            } catch (\Exception $e) {
                \Log::error('Newsletter failed for ' . $user->email . ': ' . $e->getMessage());
            }
        }

        // Log the sent newsletter
        $snapshot = $posts->map(function($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'reporter_name' => $p->reporter_name
            ];
        })->toArray();

        \App\Models\NewsletterLog::create([
            'sent_date' => now()->toDateString(),
            'recipients_count' => $count,
            'posts_snapshot' => $snapshot
        ]);

        $this->info("Successfully sent $count newsletters and logged history.");
        return 0;
    }
}
