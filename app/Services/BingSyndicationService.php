<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BingSyndicationService
{
    /**
     * Submit a URL to Bing using the IndexNow protocol and Content Submission API.
     */
    public function submitUrl(string $url)
    {
        // 1. Always ping IndexNow (no API key required, uses root key file)
        $this->pingIndexNow($url);

        // 2. Submit via Bing Content Submission API (requires API key)
        $apiKey = config('services.bing.api_key');
        if ($apiKey) {
            $this->submitToBingApi($url, $apiKey);
        } else {
            Log::info("Skipping Bing Content Submission API (BING_API_KEY missing). IndexNow was sent.");
        }
    }

    /**
     * Ping IndexNow (Supported by Bing, Yandex, etc.)
     */
    protected function pingIndexNow(string $url)
    {
        try {
            $host = parse_url($url, PHP_URL_HOST);
            $key = config('services.indexnow.key', 'ntt_news_indexnow_key'); // Should match a file on root
            
            Http::post("https://www.bing.com/indexnow", [
                'host' => $host,
                'key' => $key,
                'keyLocation' => "https://{$host}/{$key}.txt",
                'urlList' => [$url]
            ]);
            Log::info("IndexNow ping sent for: {$url}");
        } catch (\Exception $e) {
            Log::error("IndexNow ping failed: " . $e->getMessage());
        }
    }

    /**
     * Submit directly to Bing Content Submission API
     */
    protected function submitToBingApi(string $url, string $apiKey)
    {
        try {
            $siteUrl = 'https://newsthetruth.com';
            Http::post("https://www.bing.com/webmaster/api.svc/json/SubmitUrl?apikey={$apiKey}", [
                'siteUrl' => $siteUrl,
                'url' => $url
            ]);
            Log::info("Bing API submission sent for: {$url}");
        } catch (\Exception $e) {
            Log::error("Bing API submission failed: " . $e->getMessage());
        }
    }
}
