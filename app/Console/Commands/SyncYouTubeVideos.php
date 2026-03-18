<?php

namespace App\Console\Commands;

use App\Services\YouTubeSyncService;
use Illuminate\Console\Command;

class SyncYouTubeVideos extends Command
{
    protected $signature = 'ntt:sync-youtube';
    protected $description = 'Sync latest videos from the configured YouTube channel';

    public function handle(YouTubeSyncService $syncService)
    {
        $this->info('Starting YouTube sync...');
        $count = $syncService->syncChannelVideos();
        $this->info("Successfully synced {$count} new videos.");
    }
}
