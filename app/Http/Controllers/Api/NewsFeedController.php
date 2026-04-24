<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Response;

class NewsFeedController extends Controller
{
    /**
     * Generate an RSS feed specifically for Google News.
     */
    public function rss()
    {
        $posts = Post::where('status', 'published')
            ->where('visibility', 'public')
            ->with(['thumbnailMedia', 'user']) // Eager load
            ->latest()
            ->limit(50)
            ->get();

        $siteUrl = env('FRONTEND_URL', 'https://newsthetruth.com');
        $siteTitle = 'News The Truth';
        $siteDescription = 'Authentic storytelling and citizen journalism from NTT Newsroom.';

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:media="http://search.yahoo.com/mrss/"></rss>');
        
        $channel = $xml->addChild('channel');
        $channel->addChild('title', $siteTitle);
        $channel->addChild('link', $siteUrl);
        $channel->addChild('description', $siteDescription);
        $channel->addChild('language', 'en-IN');
        $channel->addChild('lastBuildDate', now()->toRfc2822String());

        foreach ($posts as $post) {
            $item = $channel->addChild('item');
            
            // Use CDATA for title to handle special characters safely
            $titleNode = $item->addChild('title');
            $titleNode[0] = $post->title;
            
            $item->addChild('link', $siteUrl . '/news/' . $post->slug);
            
            // Use CDATA for description
            $descNode = $item->addChild('description');
            $descNode[0] = $post->excerpt ?? strip_tags($post->description);
            
            $item->addChild('pubDate', $post->created_at->toRfc2822String());
            $item->addChild('guid', $siteUrl . '/news/' . $post->slug)->addAttribute('isPermaLink', 'true');
            
            // Add author
            $dcNamespace = 'http://purl.org/dc/elements/1.1/';
            $item->addChild('dc:creator', $post->reporter_name ?? $post->user?->name ?? 'NTT Desk', $dcNamespace);

            // Add full content for Google News using CDATA
            $contentNamespace = 'http://purl.org/rss/1.0/modules/content/';
            $contentNode = $item->addChild('content:encoded', '', $contentNamespace);
            $contentNode[0] = $post->description;

            // Add Thumbnail if available
            if ($post->thumbnailMedia && $post->thumbnailMedia->url) {
                $imageUrl = rtrim(config('app.url', 'https://backend.newsthetruth.com'), '/') . '/storage/' . ltrim($post->thumbnailMedia->url, '/');
                
                $enclosure = $item->addChild('enclosure');
                $enclosure->addAttribute('url', $imageUrl);
                $enclosure->addAttribute('type', 'image/jpeg');
                
                $mediaNamespace = 'http://search.yahoo.com/mrss/';
                $mediaContent = $item->addChild('media:content', '', $mediaNamespace);
                $mediaContent->addAttribute('url', $imageUrl);
                $mediaContent->addAttribute('medium', 'image');
            }
        }

        return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
    }
}
