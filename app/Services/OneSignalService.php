<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneSignalService
{
    protected string $appId;
    protected string $restApiKey;
    protected string $apiUrl = 'https://onesignal.com/api/v1/notifications';

    public function __construct()
    {
        $this->appId      = config('services.onesignal.app_id', '');
        $this->restApiKey = config('services.onesignal.rest_api_key', '');
    }

    /**
     * Send a push notification to all subscribed NTT users.
     *
     * @param  string      $title   The article headline
     * @param  string      $message The article excerpt
     * @param  string      $url     The full URL to the article
     * @param  string|null $imageUrl Optional large image for the notification
     * @return bool
     */
    public function sendToAll(string $title, string $message, string $url, ?string $imageUrl = null): bool
    {
        if (empty($this->appId) || empty($this->restApiKey)) {
            Log::warning('[OneSignal] App ID or REST API Key is missing. Skipping push notification.');
            return false;
        }

        $payload = [
            'app_id'             => $this->appId,
            'included_segments'  => ['All'],
            'headings'           => ['en' => $title],
            'contents'           => ['en' => $message],
            'url'                => $url,
            'chrome_web_icon'    => asset('/icon-192.png'),
            'firefox_icon'       => asset('/icon-192.png'),
        ];

        if (!empty($imageUrl)) {
            $payload['big_picture']       = $imageUrl;
            $payload['chrome_web_image']  = $imageUrl;
            $payload['adm_big_picture']   = $imageUrl;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->restApiKey,
                'Content-Type'  => 'application/json',
            ])->post($this->apiUrl, $payload);

            if ($response->successful()) {
                Log::info('[OneSignal] Push notification sent. ID: ' . $response->json('id'));
                return true;
            }

            Log::error('[OneSignal] API Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('[OneSignal] Exception: ' . $e->getMessage());
            return false;
        }
    }
}
