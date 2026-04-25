<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\JustIn;
use App\Models\SiteSetting;
use App\Models\CitizenJournalism;
use App\Http\Controllers\NotificationController;

class ApiController extends Controller
{
    /**
     * Get the latest posts.
     */
    public function latestPosts(Request $request)
    {
        $limit = $request->get('limit', 10);
        $posts = Post::where('status', 'published')
            ->with(['thumbnails', 'categories.cat_data', 'user.details', 'user.thumbnails'])
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
            ->with(['thumbnails', 'categories.cat_data', 'user.details', 'user.thumbnails'])
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
        // Case-insensitive search: try both original and uppercase
        $category = Category::where('slug', $slug)
            ->orWhere('slug', strtolower($slug))
            ->orWhere('slug', strtoupper($slug))
            ->first();
            
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }

        $limit = $request->get('limit', 20);
        $posts = Post::where('status', 'published')
            ->whereHas('categories.cat_data', function ($q) use ($category) {
                $q->where('id', $category->id);
            })
            ->with(['thumbnails', 'categories.cat_data', 'user.details', 'user.thumbnails'])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

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
            ->with(['thumbnails', 'categories.cat_data', 'metas', 'user.details', 'user.thumbnails', 'tags.tag_data', 'gallery'])
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
     * Get a user's details by ID.
     */
    public function user($id)
    {
        $user = \App\Models\User::where('id', $id)
            ->with(['details', 'thumbnails'])
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Get posts by a specific user ID.
     */
    public function userPosts($id, Request $request)
    {
        $limit = $request->get('limit', 20);
        $posts = Post::where('user_id', $id)
            ->where('status', 'published')
            ->with(['thumbnails', 'categories.cat_data', 'user.details', 'user.thumbnails'])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
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

    /**
     * Submit a citizen journalism report.
     */
    public function citizenReport(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'email' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'attachment_file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi,mkv|max:51200',
        ]);

        $post = new \App\Models\CitizenJournalism();
        $post->user_id = 0; // Anonymous or guest
        $post->title = $request->title;
        $post->place = $request->location;
        $post->description = $request->description;
        $post->subtitle = $request->email;
        $post->credit = $request->name ?? 'Anonymous';
        $post->datetime = now();

        if ($request->hasFile('attachment_file')) {
            $file = $request->file('attachment_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'public/uploads/citizen';

            if (!file_exists(base_path($path))) {
                mkdir(base_path($path), 0755, true);
            }

            $file->move(base_path($path), $filename);
            $post->attachment_url = url($path . '/' . $filename);
        }

        $post->save();

        // Trigger notification and Email Alert
        try {
            // DB Notification
            $notification = new NotificationController();
            $notification->description('New API Citizen Report: ' . $post->title);
            $notification->type('users');
            $notification->send();

            // Email Alert to Admin
            $adminEmail = env('MAIL_FROM_ADDRESS', 'newsthetruthindia@gmail.com');
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($post, $adminEmail) {
                $message->to($adminEmail)
                    ->subject('NEW CITIZEN REPORT: ' . $post->title)
                    ->html("
                        <div style='font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:40px 20px;'>
                            <h2 style='color:#8c0000;'>New Citizen Journalism Report</h2>
                            <p><strong>Title:</strong> {$post->title}</p>
                            <p><strong>Location:</strong> {$post->place}</p>
                            <p><strong>Reporter:</strong> {$post->credit} ({$post->subtitle})</p>
                            <hr style='border:none;border-top:1px solid #eee;margin:20px 0;'>
                            <p style='white-space:pre-wrap;'>{$post->description}</p>
                            " . ($post->attachment_url ? "<p><a href='{$post->attachment_url}' style='background:#111;color:white;padding:10px 20px;text-decoration:none;border-radius:4px;'>View Attachment</a></p>" : "") . "
                        </div>
                    ");
            });
        } catch (\Exception $e) {
            // Silently fail if mail fails (prevent crashing the API)
        }

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully. Thank you for sharing the truth.',
            'data' => $post
        ]);
    }

    /**
     * Get all tags.
     */
    public function tags()
    {
        $tags = \App\Models\Tag::all();
        return response()->json([
            'success' => true,
            'data' => $tags
        ]);
    }

    /**
     * Search for posts by title or content.
     */
    public function searchPosts(Request $request)
    {
        $query = $request->get('q');
        if (!$query) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $limit = $request->get('limit', 20);
        $posts = Post::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%");
            })
            ->with(['thumbnails', 'categories.cat_data', 'user.details', 'user.thumbnails'])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Get posts by a specific date.
     */
    public function archivePosts(Request $request)
    {
        $date = $request->get('date'); // Expects YYYY-MM-DD
        if (!$date) {
            return response()->json(['success' => false, 'message' => 'Date is required'], 400);
        }

        $limit = $request->get('limit', 20);
        $posts = Post::whereDate('created_at', $date)
            ->where('status', 'published')
            ->with(['thumbnails', 'categories.cat_data', 'user.details', 'user.thumbnails'])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }
    /**
     * Get all videos.
     */
    public function videos()
    {
        $videos = \App\Models\Video::orderBy('sort_order', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $videos
        ]);
    }

    public function archiveSummary()
    {
        $total = Post::where('status', 'published')->count();
        $rounded = floor($total / 100) * 100;
        
        return response()->json([
            'success' => true,
            'data' => [
                'total_posts' => $total,
                'rounded_count' => number_format($rounded) . '+',
                'years_count' => '3+'
            ]
        ]);
    }

    public function activeReporters()
    {
        $reporterNames = Post::where('status', 'published')
            ->whereNotNull('reporter_name')
            ->whereNotIn('reporter_name', ['Staff Reporter', 'Citizen Journalist'])
            ->distinct()
            ->pluck('reporter_name');

        $reporters = \App\Models\User::where(function($q) use ($reporterNames) {
                $q->whereIn(\Illuminate\Support\Facades\DB::raw("trim(concat(firstname, ' ', coalesce(lastname, '')))"), $reporterNames)
                  ->orWhereHas('roles', fn($qr) => $qr->where('name', 'Reporter'));
            })
            ->with(['details', 'thumbnails'])
            ->get();

        $safeData = $reporters->map(fn($u) => [
            'id'          => $u->id,
            'firstname'   => $u->firstname,
            'lastname'    => $u->lastname,
            'is_reporter' => true,
            'details'     => [
                'designation' => $u->details?->designation ?? 'Reporter',
                'bio'         => $u->details?->bio,
            ],
            'thumbnails'  => $u->thumbnails,
        ]);

        return response()->json(['success' => true, 'data' => $safeData]);
    }

    /**
     * Track post views and shares.
     */
    public function track(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'type'    => 'required|in:view,share'
        ]);

        $postId = $request->post_id;
        $type   = $request->type;

        if ($type === 'view') {
            // Increment view count in post_views table for today
            $today = now()->format('Y-m-d');
            
            \Illuminate\Support\Facades\DB::table('post_views')->updateOrInsert(
                ['post_id' => $postId, 'created_at' => $today],
                ['viewer_count' => \Illuminate\Support\Facades\DB::raw('viewer_count + 1'), 'updated_at' => now()]
            );
        } else {
            // Increment share count in posts table
            Post::where('id', $postId)->increment('shares');
        }

        return response()->json(['success' => true]);
    }
}
