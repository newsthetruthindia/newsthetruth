<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\JustIn;
use App\Models\SiteSetting;

class ApiController extends Controller
{
    /**
     * Get the latest posts.
     */
    public function latestPosts(Request $request)
    {
        $limit = $request->get('limit', 10);
        $posts = Post::where('status', 'published')
            ->where('visibility', 'public')
            ->with(['thumbnails', 'categories.cat_data'])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get the top posts.
     */
    public function topPosts(Request $request)
    {
        $limit = $request->get('limit', 6);
        $posts = Post::where('top_post', 1)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->with(['thumbnails', 'categories.cat_data'])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get posts by category slug.
     */
    public function categoryPosts($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }

        $limit = $request->get('limit', 20);
        $posts = Post::where('status', 'published')
            ->where('visibility', 'public')
            ->whereHas('categories.cat_data', function ($q) use ($category) {
                $q->where('id', $category->id);
            })
            ->with(['thumbnails', 'categories.cat_data'])
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get all categories.
     */
    public function categories()
    {
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get a single post by slug.
     */
    public function post($slug)
    {
        $post = Post::where('slug', $slug)
            ->with(['thumbnails', 'categories.cat_data', 'metas', 'user', 'tags.tag_data', 'gallery'])
            ->first();

        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    /**
     * Get basic site settings.
     */
    public function settings()
    {
        $settings = SiteSetting::pluck('description', 'key')->toArray();
        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }
}
