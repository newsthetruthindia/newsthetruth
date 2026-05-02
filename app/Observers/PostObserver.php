<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    /**
     * Handle the Post "saved" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function saved(Post $post)
    {
        $this->revalidateFrontend($post);
    }

    /**
     * Handle the Post "deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function deleted(Post $post)
    {
        $this->revalidateFrontend($post);
    }

    /**
     * Trigger revalidation on the Next.js frontend.
     */
    protected function revalidateFrontend(Post $post)
    {
        $token = config('services.nextjs.revalidation_token');
        $siteUrl = config('services.nextjs.site_url', 'https://newsthetruth.com');

        if (!$token) {
            Log::warning('Next.js Revalidation Token not set in .env');
            return;
        }

        // 1. Revalidate Homepage
        $this->triggerRevalidate($siteUrl, '/', $token);

        // 2. Revalidate the Category page of the post
        $category = $post->categories()->first();
        if ($category && isset($category->slug)) {
            $this->triggerRevalidate($siteUrl, "/category/{$category->slug}", $token);
        }

        // 3. Revalidate the Article itself
        if ($post->slug) {
            $this->triggerRevalidate($siteUrl, "/news/{$post->slug}", $token);
        }
        
        // 4. Revalidate Sitemaps
        $this->triggerRevalidate($siteUrl, "/sitemap-news.xml", $token);
    }

    protected function triggerRevalidate($siteUrl, $path, $token)
    {
        try {
            Http::post("{$siteUrl}/api/revalidate?secret={$token}&path={$path}");
        } catch (\Exception $e) {
            Log::error("Failed to revalidate path {$path}: " . $e->getMessage());
        }
    }
}
