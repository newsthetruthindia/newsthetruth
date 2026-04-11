<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserMonitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PublicMonitorController extends Controller
{
    /**
     * Get the monitor configuration via secret key.
     */
    public function show($key)
    {
        $monitor = UserMonitor::where('secret_key', $key)->firstOrFail();

        return response()->json([
            'youtube_ids' => collect($monitor->youtube_urls)->map(fn($url) => $this->getYoutubeId($url))->toArray(),
            'rss_feeds' => $this->getRssHeadlines($monitor->rss_feeds),
        ]);
    }

    private function getYoutubeId($url)
    {
        if (!$url) return null;
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches);
        return $matches[1] ?? null;
    }

    private function getRssHeadlines(array $feeds): array
    {
        $allFeeds = [];
        foreach ($feeds as $index => $feedUrl) {
            if (empty($feedUrl)) {
                $allFeeds[$index] = [];
                continue;
            }

            $cacheKey = 'rss_monitor_api_' . md5($feedUrl);
            $allFeeds[$index] = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($feedUrl) {
                try {
                    $response = Http::timeout(5)->get($feedUrl);
                    if ($response->failed()) return [];
                    
                    $xml = simplexml_load_string($response->body());
                    if (!$xml) return [];
                    
                    $items = [];
                    foreach (($xml->channel->item ?? []) as $item) {
                        $items[] = [
                            'title' => (string)$item->title,
                            'link' => (string)$item->link,
                        ];
                        if (count($items) >= 15) break;
                    }
                    return $items;
                } catch (\Exception $e) {
                    return [];
                }
            });
        }
        return $allFeeds;
    }
}
