<?php

namespace App\Jobs;

use App\Models\BroadcastLog;
use App\Models\User;
use App\Notifications\BroadcastNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class BroadcastYoutubeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $title;
    public $url;
    public $logId;

    public function __construct($title, $url, $logId)
    {
        $this->title = $title;
        $this->url = $url;
        $this->logId = $logId;
    }

    public function handle(): void
    {
        $log = BroadcastLog::find($this->logId);
        if (!$log) return;

        try {
            $log->update(['status' => 'processing']);

            User::where('type', 'user')->chunk(100, function ($subscribers) {
                Notification::send($subscribers, new BroadcastNotification($this->title, $this->url));
            });

            $log->update(['status' => 'completed']);
        } catch (\Exception $e) {
            $log->update(['status' => 'failed']);
            throw $e;
        }
    }
}
