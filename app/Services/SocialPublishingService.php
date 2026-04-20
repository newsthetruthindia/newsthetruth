<?php

namespace App\Services;

use App\Models\Option;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocialPublishingService
{
    /**
     * Publish a post to Facebook Page.
     * 
     * @param string $message The custom text to post
     * @param string $link The URL of the article
     * @return array|false Returns response data on success, false on failure
     */
    public function publishToFacebook(string $message, string $link)
    {
        $pageId = Option::where('key', 'fb_page_id')->first()?->value;
        $accessToken = Option::where('key', 'fb_access_token')->first()?->value;

        if (!$pageId || !$accessToken) {
            Log::error('SocialPublishingService: Missing Facebook Page ID or Access Token.');
            return false;
        }

        try {
            $response = Http::post("https://graph.facebook.com/v19.0/{$pageId}/feed", [
                'message'      => $message,
                'link'         => $link,
                'access_token' => $accessToken,
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Facebook API Error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Facebook Publisher Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Publish a post to Instagram Professional Account.
     * Note: Requires the Instagram Business Account ID attached to the Facebook Page.
     */
    public function publishToInstagram(string $caption, string $imageUrl)
    {
        $igAccountId = Option::where('key', 'ig_account_id')->first()?->value;
        $accessToken = Option::where('key', 'fb_access_token')->first()?->value;

        if (!$igAccountId || !$accessToken) {
            Log::error('SocialPublishingService: Missing Instagram Account ID or Access Token.');
            return false;
        }

        try {
            // Step 1: Create Media Container
            $containerResponse = Http::post("https://graph.facebook.com/v19.0/{$igAccountId}/media", [
                'image_url'    => $imageUrl,
                'caption'      => $caption,
                'access_token' => $accessToken,
            ]);

            if (!$containerResponse->successful()) {
                Log::error('Instagram API Error (Container Creation): ' . $containerResponse->body());
                return false;
            }

            $creationId = $containerResponse->json('id');

            // Step 2: Publish the Container
            $publishResponse = Http::post("https://graph.facebook.com/v19.0/{$igAccountId}/media_publish", [
                'creation_id'  => $creationId,
                'access_token' => $accessToken,
            ]);

            if ($publishResponse->successful()) {
                return $publishResponse->json();
            } else {
                Log::error('Instagram API Error (Publishing): ' . $publishResponse->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Instagram Publisher Exception: ' . $e->getMessage());
            return false;
        }
    }
}
