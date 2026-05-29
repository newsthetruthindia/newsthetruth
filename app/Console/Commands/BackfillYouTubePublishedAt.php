<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class BackfillYouTubePublishedAt extends Command
{
    protected $signature = 'ntt:backfill-youtube-published-at';
    protected $description = 'Backfill published_at dates for all videos from the YouTube API';

    public function handle()
    {
        $apiKey = config('services.youtube.key');
        if (!$apiKey) {
            $this->error('YouTube API key is missing in configuration.');
            return 1;
        }

        $videos = Video::all();
        $total = $videos->count();
        if ($total === 0) {
            $this->info('No videos found in the database.');
            return 0;
        }

        $this->info("Found {$total} videos. Starting backfill in batches of 50...");

        // Chunk videos by 50
        $chunks = $videos->chunk(50);
        $updatedCount = 0;

        foreach ($chunks as $chunk) {
            $youtubeIds = $chunk->pluck('youtube_id')->filter()->toArray();
            if (empty($youtubeIds)) {
                continue;
            }

            try {
                $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
                    'key' => $apiKey,
                    'id' => implode(',', $youtubeIds),
                    'part' => 'snippet',
                ]);

                if (!$response->successful()) {
                    $this->error('YouTube API request failed: ' . $response->body());
                    continue;
                }

                $items = $response->json('items') ?? [];
                $publishDates = [];
                foreach ($items as $item) {
                    $publishDates[$item['id']] = $item['snippet']['publishedAt'] ?? null;
                }

                foreach ($chunk as $video) {
                    if (isset($publishDates[$video->youtube_id])) {
                        $publishedAt = \Carbon\Carbon::parse($publishDates[$video->youtube_id])->toDateTimeString();
                        $video->update(['published_at' => $publishedAt]);
                        $updatedCount++;
                    } else {
                        // Fallback if video not found on YouTube (e.g. deleted or private)
                        if (!$video->published_at) {
                            $video->update(['published_at' => $video->created_at]);
                        }
                        $this->warn("Video ID {$video->youtube_id} ({$video->title}) not found on YouTube. Defaulted to created_at.");
                    }
                }

                $this->info("Processed batch. Total updated so far: {$updatedCount}/{$total}");
            } catch (\Exception $e) {
                $this->error("Error processing batch: " . $e->getMessage());
            }
        }

        $this->info("Backfill complete! Updated {$updatedCount} videos.");
        return 0;
    }
}
