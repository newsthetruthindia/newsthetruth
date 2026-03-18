<?php

namespace App\Services;

use App\Models\Video;
use Google\Client;
use Google\Service\YouTube;
use Illuminate\Support\Facades\Log;

class YouTubeSyncService
{
    protected $youtube;

    public function __construct()
    {
        $client = new Client();
        $client->setDeveloperKey(config('services.youtube.key'));
        $this->youtube = new YouTube($client);
    }

    public function syncChannelVideos()
    {
        $channelId = config('services.youtube.channel_id');
        if (!$channelId) {
            Log::warning('YouTube Sync: Channel ID not configured.');
            return 0;
        }

        try {
            // Fetch latest 20 items to ensure we get both videos and reels
            $response = $this->youtube->search->listSearch('snippet', [
                'channelId' => $channelId,
                'maxResults' => 20,
                'order' => 'date',
                'type' => 'video',
            ]);

            $videoIds = [];
            $items = $response->getItems();
            foreach ($items as $item) {
                $videoIds[] = $item->id->videoId;
            }

            // Fetch details to check duration (Shorts are < 60s)
            $detailsResponse = $this->youtube->videos->listVideos('contentDetails', [
                'id' => implode(',', $videoIds)
            ]);

            $durations = [];
            foreach ($detailsResponse->getItems() as $detailItem) {
                $durations[$detailItem->getId()] = $detailItem->getContentDetails()->getDuration();
            }

            $syncedCount = 0;
            foreach ($items as $item) {
                $videoId = $item->id->videoId;
                $title = $item->snippet->title;
                
                // ISO 8601 duration check (e.g. PT58S is a short)
                $duration = $durations[$videoId] ?? '';
                $isReel = false;
                
                // Simple check for < 1 minute (no 'M' or 'H' in PTxxS)
                if (strpos($duration, 'M') === false && strpos($duration, 'H') === false) {
                    $isReel = true;
                }

                $video = Video::updateOrCreate(
                    ['youtube_id' => $videoId],
                    [
                        'title' => $title, 
                        'type' => $isReel ? 'reel' : 'video',
                        'sort_order' => 0
                    ]
                );

                if ($video->wasRecentlyCreated) {
                    $syncedCount++;
                }
            }

            return $syncedCount;
        } catch (\Exception $e) {
            Log::error('YouTube Sync Error: ' . $e->getMessage());
            return 0;
        }
    }
}
