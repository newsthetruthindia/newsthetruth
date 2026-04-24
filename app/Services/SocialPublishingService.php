<?php

namespace App\Services;

use App\Models\Option;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocialPublishingService
{
    /**
     * Current Graph API version — keep this updated.
     */
    private const API_VERSION = 'v21.0';

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
        $accessToken = Option::where('key', 'fb_page_access_token')->first()?->value
                    ?? Option::where('key', 'fb_access_token')->first()?->value;

        if (!$pageId || !$accessToken) {
            Log::error('SocialPublishingService: Missing Facebook Page ID or Access Token. Configure in Admin > Social Publishing.');
            return false;
        }

        try {
            Log::info("SocialPublishingService: Publishing to Facebook Page {$pageId}");

            $response = Http::post("https://graph.facebook.com/" . self::API_VERSION . "/{$pageId}/feed", [
                'message'      => $message,
                'link'         => $link,
                'access_token' => $accessToken,
            ]);

            if ($response->successful()) {
                Log::info("SocialPublishingService: Facebook post successful. Post ID: " . ($response->json('id') ?? 'unknown'));
                return $response->json();
            }

            $error = $response->json('error') ?? [];
            $errorCode = $error['code'] ?? 0;
            $errorMsg = $error['message'] ?? 'Unknown error';

            // Token expired
            if ($errorCode == 190) {
                Log::error("SocialPublishingService: Facebook ACCESS TOKEN EXPIRED. Go to Admin > Settings > Social Publishing and generate a new long-lived token. Error: {$errorMsg}");
            }
            // Permission issue
            elseif ($errorCode == 200) {
                Log::error("SocialPublishingService: Facebook PERMISSION DENIED. Ensure your token has pages_manage_posts and pages_read_engagement permissions. Error: {$errorMsg}");
            }
            else {
                Log::error("SocialPublishingService: Facebook API Error [{$errorCode}]: {$errorMsg}");
            }

            return false;
        } catch (\Exception $e) {
            Log::error('SocialPublishingService: Facebook Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Publish a post to Instagram Professional Account.
     * Requires the Instagram Business Account ID attached to the Facebook Page.
     *
     * @param string $caption The caption for the Instagram post
     * @param string $imageUrl A publicly accessible image URL
     * @return array|false
     */
    public function publishToInstagram(string $caption, string $imageUrl)
    {
        $igAccountId = Option::where('key', 'ig_account_id')->first()?->value;
        $accessToken = Option::where('key', 'fb_page_access_token')->first()?->value
                    ?? Option::where('key', 'fb_access_token')->first()?->value;

        if (!$igAccountId || !$accessToken) {
            Log::error('SocialPublishingService: Missing Instagram Account ID or Access Token.');
            return false;
        }

        // Ensure the image URL is fully qualified and publicly accessible
        $imageUrl = $this->resolvePublicImageUrl($imageUrl);

        Log::info("SocialPublishingService: Publishing to Instagram {$igAccountId} with image: {$imageUrl}");

        try {
            // Step 1: Create Media Container
            $containerResponse = Http::post("https://graph.facebook.com/" . self::API_VERSION . "/{$igAccountId}/media", [
                'image_url'    => $imageUrl,
                'caption'      => $caption,
                'access_token' => $accessToken,
            ]);

            if (!$containerResponse->successful()) {
                $error = $containerResponse->json('error') ?? [];
                $errorCode = $error['code'] ?? 0;
                $errorMsg = $error['message'] ?? 'Unknown error';
                $userMsg = $error['error_user_msg'] ?? '';

                if ($errorCode == 190) {
                    Log::error("SocialPublishingService: Instagram TOKEN EXPIRED. Regenerate in Admin > Social Publishing.");
                } elseif ($errorCode == 9004 || $errorCode == 36001) {
                    Log::error("SocialPublishingService: Instagram IMAGE REJECTED. The URL may not be publicly accessible or the format is unsupported. URL: {$imageUrl}. Detail: {$userMsg}");
                } else {
                    Log::error("SocialPublishingService: Instagram Container Error [{$errorCode}]: {$errorMsg}");
                }

                return false;
            }

            $creationId = $containerResponse->json('id');

            if (!$creationId) {
                Log::error('SocialPublishingService: Instagram container created but no creation ID returned.');
                return false;
            }

            // Step 2: Wait briefly for Instagram to process the image
            sleep(3);

            // Step 3: Publish the Container
            $publishResponse = Http::post("https://graph.facebook.com/" . self::API_VERSION . "/{$igAccountId}/media_publish", [
                'creation_id'  => $creationId,
                'access_token' => $accessToken,
            ]);

            if ($publishResponse->successful()) {
                Log::info("SocialPublishingService: Instagram post successful. Media ID: " . ($publishResponse->json('id') ?? 'unknown'));
                return $publishResponse->json();
            }

            Log::error('SocialPublishingService: Instagram Publish Error: ' . $publishResponse->body());
            return false;

        } catch (\Exception $e) {
            Log::error('SocialPublishingService: Instagram Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Exchange a short-lived user token for a never-expiring Page Access Token.
     *
     * Steps:
     * 1. Short-lived user token → Long-lived user token (60 days)
     * 2. Long-lived user token → Never-expiring page token
     *
     * @param string $shortLivedToken The token from Graph API Explorer
     * @param string $appId Facebook App ID
     * @param string $appSecret Facebook App Secret
     * @param string $pageId Facebook Page ID
     * @return array{success: bool, token?: string, error?: string}
     */
    public function exchangeForPageToken(string $shortLivedToken, string $appId, string $appSecret, string $pageId): array
    {
        try {
            // Step 1: Exchange for long-lived user token
            Log::info('SocialPublishingService: Step 1 — Exchanging for long-lived user token...');

            $llResponse = Http::get("https://graph.facebook.com/" . self::API_VERSION . "/oauth/access_token", [
                'grant_type'        => 'fb_exchange_token',
                'client_id'         => $appId,
                'client_secret'     => $appSecret,
                'fb_exchange_token' => $shortLivedToken,
            ]);

            if (!$llResponse->successful()) {
                $error = $llResponse->json('error.message') ?? $llResponse->body();
                Log::error("SocialPublishingService: Token exchange failed at Step 1: {$error}");
                return ['success' => false, 'error' => "Step 1 failed: {$error}"];
            }

            $longLivedUserToken = $llResponse->json('access_token');

            if (!$longLivedUserToken) {
                return ['success' => false, 'error' => 'Step 1 returned empty token.'];
            }

            Log::info('SocialPublishingService: Step 1 complete. Got long-lived user token.');

            // Step 2: Get never-expiring page token
            Log::info('SocialPublishingService: Step 2 — Fetching page access tokens...');

            $pagesResponse = Http::get("https://graph.facebook.com/" . self::API_VERSION . "/me/accounts", [
                'access_token' => $longLivedUserToken,
            ]);

            if (!$pagesResponse->successful()) {
                $error = $pagesResponse->json('error.message') ?? $pagesResponse->body();
                Log::error("SocialPublishingService: Token exchange failed at Step 2: {$error}");
                return ['success' => false, 'error' => "Step 2 failed: {$error}"];
            }

            $pages = $pagesResponse->json('data') ?? [];

            // Find the matching page
            $pageToken = null;
            $pageName = null;
            foreach ($pages as $page) {
                if ($page['id'] === $pageId) {
                    $pageToken = $page['access_token'];
                    $pageName = $page['name'] ?? 'Unknown';
                    break;
                }
            }

            if (!$pageToken) {
                $availablePages = collect($pages)->pluck('name', 'id')->toArray();
                Log::error("SocialPublishingService: Page ID {$pageId} not found. Available pages: " . json_encode($availablePages));
                return [
                    'success' => false,
                    'error' => "Page ID {$pageId} not found in your accounts. Available: " . implode(', ', array_map(fn($name, $id) => "{$name} ({$id})", $availablePages, array_keys($availablePages))),
                ];
            }

            // Store the never-expiring page token
            Option::updateOrCreate(['key' => 'fb_page_access_token'], ['value' => $pageToken]);

            Log::info("SocialPublishingService: Successfully obtained never-expiring token for page: {$pageName}");

            return [
                'success' => true,
                'token' => $pageToken,
                'page_name' => $pageName,
            ];

        } catch (\Exception $e) {
            Log::error('SocialPublishingService: Token exchange exception: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Validate the current stored token by calling the Graph API.
     *
     * @return array{valid: bool, page_name?: string, error?: string}
     */
    public function validateToken(): array
    {
        $pageId = Option::where('key', 'fb_page_id')->first()?->value;
        $accessToken = Option::where('key', 'fb_page_access_token')->first()?->value
                    ?? Option::where('key', 'fb_access_token')->first()?->value;

        if (!$pageId || !$accessToken) {
            return ['valid' => false, 'error' => 'Missing Page ID or Access Token.'];
        }

        try {
            $response = Http::get("https://graph.facebook.com/" . self::API_VERSION . "/{$pageId}", [
                'fields' => 'name,access_token',
                'access_token' => $accessToken,
            ]);

            if ($response->successful()) {
                return ['valid' => true, 'page_name' => $response->json('name') ?? 'Connected'];
            }

            $error = $response->json('error.message') ?? 'Unknown error';
            return ['valid' => false, 'error' => $error];

        } catch (\Exception $e) {
            return ['valid' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Ensure the image URL is a fully qualified, publicly accessible URL.
     * Instagram's API fetches the image from our server, so it must be reachable.
     */
    private function resolvePublicImageUrl(string $url): string
    {
        // Already a full URL
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        // Build from APP_URL (backend domain)
        $baseUrl = rtrim(config('app.url', 'https://backend.newsthetruth.com'), '/');

        // Handle paths like "uploads/media/file.jpg" or "/uploads/media/file.jpg"
        $path = ltrim($url, '/');

        // If the file is in storage, it should be served via /storage/ symlink
        if (str_starts_with($path, 'uploads/')) {
            return "{$baseUrl}/storage/{$path}";
        }

        return "{$baseUrl}/{$path}";
    }
}
