<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class YouTubeSyncService
{
    public function syncChannelVideos()
    {
        $apiKey = config('services.youtube.key');
        $channelId = config('services.youtube.channel_id');

        if (!$channelId || !$apiKey) {
            Log::warning('YouTube Sync: Configuration missing.');
            return 0;
        }

        try {
            // 1. Search for latest videos
            $searchResponse = Http::get('https://www.googleapis.com/youtube/v3/search', [
                'key' => $apiKey,
                'channelId' => $channelId,
                'part' => 'snippet',
                'maxResults' => 20,
                'order' => 'date',
                'type' => 'video',
            ]);

            if (!$searchResponse->successful()) {
                throw new \Exception('Search API failed: ' . $searchResponse->body());
            }

            $items = $searchResponse->json('items');
            $videoIds = [];
            foreach ($items as $item) {
                $videoIds[] = $item['id']['videoId'];
            }

            if (empty($videoIds)) return 0;

            // 2. Get video details (for duration check)
            $detailsResponse = Http::get('https://www.googleapis.com/youtube/v3/videos', [
                'key' => $apiKey,
                'id' => implode(',', $videoIds),
                'part' => 'contentDetails,snippet',
            ]);

            if (!$detailsResponse->successful()) {
                throw new \Exception('Videos API failed: ' . $detailsResponse->body());
            }

            $details = $detailsResponse->json('items');
            $videoData = [];
            foreach ($details as $detail) {
                $videoData[$detail['id']] = [
                    'title' => $detail['snippet']['title'],
                    'duration' => $detail['contentDetails']['duration'],
                ];
            }

            $syncedCount = 0;
            foreach ($videoData as $videoId => $data) {
                // ISO 8601 duration check (Shorts are < 60s)
                $duration = $data['duration'];
                // Simple check for < 1 minute (no 'M' or 'H' in PTxxS)
                $isReel = (strpos($duration, 'M') === false && strpos($duration, 'H') === false);

                $video = Video::updateOrCreate(
                    ['youtube_id' => $videoId],
                    [
                        'title' => $data['title'],
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
