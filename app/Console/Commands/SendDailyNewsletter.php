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
        $this->info('Fetching top 5 recent posts...');
        
        $posts = Post::where('status', 'published') // assuming 'status' column, will check later
                     ->orderBy('id', 'desc')
                     ->take(5)
                     ->get();

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

        $this->info("Successfully sent $count newsletters.");
        return 0;
    }
}
