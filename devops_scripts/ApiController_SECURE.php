<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\SiteSetting;

class ApiController extends Controller
{
    // SECURITY: Maximum allowed limit for any list endpoint.
    // Prevents DoS via memory exhaustion (CRIT-2).
    const MAX_LIMIT = 50;

    // SECURITY: Fields safe to expose in public user objects.
    // Never expose: password, salary, pass_otp, v_code, google2fa_secret, phone, address
    const SAFE_USER_FIELDS = ['id', 'firstname', 'lastname', 'bio', 'designation', 'is_reporter'];

    private function safeLimit(Request $request, int $default = 10): int
    {
        $limit = (int) $request->get('limit', $default);
        return min(max($limit, 1), self::MAX_LIMIT);
    }

    private function sanitizeUser(?array $user): ?array
    {
        if (!$user) return null;
        return array_intersect_key($user, array_flip([
            'id', 'firstname', 'lastname', 'is_reporter',
            'details' => ['bio', 'designation'],
            'thumbnails',
        ]));
    }

    public function latestPosts(Request $request)
    {
        $limit = $this->safeLimit($request, 10);
        $posts = Post::where('status', 'published')
            ->with(['thumbnails', 'categories.cat_data', 'user.thumbnails', 'user.details'])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

        // SECURITY (MED-5): Strip sensitive user fields from each post
        $safeData = $posts->map(fn($p) => $this->stripSensitiveUserData($p));

        return response()->json(['success' => true, 'data' => $safeData]);
    }

    public function topPosts(Request $request)
    {
        $limit = $this->safeLimit($request, 6);
        $posts = Post::where('top_post', 1)
            ->where('status', 'published')
            ->with(['thumbnails', 'categories.cat_data', 'user.thumbnails', 'user.details'])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

        $safeData = $posts->map(fn($p) => $this->stripSensitiveUserData($p));
        return response()->json(['success' => true, 'data' => $safeData]);
    }

    public function categoryPosts($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }

        $limit = $this->safeLimit($request, 20);
        $posts = Post::where('status', 'published')
            ->whereHas('categories.cat_data', function ($q) use ($category) {
                $q->where('id', $category->id);
            })
            ->with(['thumbnails', 'categories.cat_data', 'user.thumbnails', 'user.details'])
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        $posts->getCollection()->transform(fn($p) => $this->stripSensitiveUserData($p));
        return response()->json(['success' => true, 'data' => $posts]);
    }

    public function categories()
    {
        return response()->json(['success' => true, 'data' => Category::all()]);
    }

    public function post($slug)
    {
        $post = Post::where('slug', $slug)
            ->with(['thumbnails', 'categories.cat_data', 'metas', 'user.details', 'user.thumbnails', 'tags.tag_data', 'gallery'])
            ->first();

        if (!$post) {
            return response()->json(['success' => false, 'message' => 'Post not found'], 404);
        }

        $safePost = $this->stripSensitiveUserData($post);
        return response()->json(['success' => true, 'data' => $safePost]);
    }

    /**
     * SECURITY (CRIT-1 + MED-5): Public user profile — safe fields only.
     * Requires auth:sanctum on the route for this to work properly.
     */
    public function user($id)
    {
        $user = \App\Models\User::where('id', $id)
            ->with(['details', 'thumbnails'])
            ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Only return public-safe fields
        return response()->json([
            'success' => true,
            'data' => [
                'id'          => $user->id,
                'firstname'   => $user->firstname,
                'lastname'    => $user->lastname,
                'is_reporter' => $user->is_reporter,
                'details' => [
                    'bio'         => $user->details?->bio,
                    'designation' => $user->details?->designation,
                    'twitter'     => $user->details?->twitter,
                    'facebook'    => $user->details?->facebook,
                    'instagram'   => $user->details?->instagram,
                    'linkedin'    => $user->details?->linkedin,
                ],
                'thumbnails'  => $user->thumbnails,
            ],
        ]);
    }

    public function userPosts($id, Request $request)
    {
        $limit = $this->safeLimit($request, 20);
        $posts = Post::where('user_id', $id)
            ->where('status', 'published')
            ->with(['thumbnails', 'categories.cat_data', 'user.thumbnails', 'user.details'])
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        return response()->json(['success' => true, 'data' => $posts]);
    }

    public function settings()
    {
        $settings = SiteSetting::pluck('description', 'key')->toArray();
        return response()->json(['success' => true, 'data' => $settings]);
    }

    public function citizenReport(Request $request)
    {
        $request->validate([
            'title'           => 'required|string|max:255',
            'location'        => 'required|string|max:255',
            'description'     => 'required|string|max:10000',
            'email'           => 'required|email|max:255',
            'name'            => 'nullable|string|max:255',
            // SECURITY (MED-3): Validate actual MIME types strictly
            'attachment_file' => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
        ]);

        $post = new \App\Models\CitizenJournalism();
        $post->user_id    = 0;
        $post->title      = $request->title;
        $post->place      = $request->location;
        $post->description = $request->description;
        $post->subtitle   = $request->email;
        $post->credit     = $request->name ?? 'Anonymous';
        $post->datetime   = now();

        if ($request->hasFile('attachment_file')) {
            $file = $request->file('attachment_file');
            // SECURITY (MED-3): Use a cryptographic hash for the filename (no user-controlled characters)
            $filename = hash('sha256', $file->getClientOriginalName() . time()) . '.' . $file->guessExtension();
            $file->move(storage_path('app/citizen-reports'), $filename);
            $post->attachment_url = 'citizen-reports/' . $filename;
        }

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully. Thank you for sharing the truth.',
        ]);
    }

    public function tags()
    {
        return response()->json(['success' => true, 'data' => \App\Models\Tag::all()]);
    }

    public function searchPosts(Request $request)
    {
        $query = $request->get('q');
        if (!$query) {
            return response()->json(['success' => true, 'data' => []]);
        }

        // SECURITY (LOW-3): Limit search query length to prevent ReDoS
        $query = substr($query, 0, 200);
        $limit = $this->safeLimit($request, 20);

        $posts = Post::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%");
                  // Note: removed 'description' LIKE to avoid extremely slow full-body search
            })
            ->with(['thumbnails', 'categories.cat_data', 'user.thumbnails', 'user.details'])
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->get();

        return response()->json(['success' => true, 'data' => $posts]);
    }

    public function archivePosts(Request $request)
    {
        $date = $request->get('date');
        if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return response()->json(['success' => false, 'message' => 'Valid date (YYYY-MM-DD) is required'], 400);
        }

        $limit = $this->safeLimit($request, 20);
        $posts = Post::whereDate('created_at', $date)
            ->where('status', 'published')
            ->with(['thumbnails', 'categories.cat_data', 'user.thumbnails', 'user.details'])
            ->orderBy('created_at', 'DESC')
            ->paginate($limit);

        return response()->json(['success' => true, 'data' => $posts]);
    }

    public function videos()
    {
        $videos = \App\Models\Video::orderBy('sort_order', 'ASC')->orderBy('created_at', 'DESC')->get();
        return response()->json(['success' => true, 'data' => $videos]);
    }

    /**
     * SECURITY: Strip all sensitive user fields from post data before returning.
     * Removes: salary, pass_otp, v_code, google2fa_secret, phone, address, cron, email_verified
     */
    private function stripSensitiveUserData($post): array
    {
        $data = $post->toArray();
        if (isset($data['user'])) {
            $user = $data['user'];
            $data['user'] = array_filter([
                'id'         => $user['id'] ?? null,
                'firstname'  => $user['firstname'] ?? null,
                'lastname'   => $user['lastname'] ?? null,
                'is_reporter'=> $user['is_reporter'] ?? false,
                'thumbnails' => $user['thumbnails'] ?? null,
                'details'    => isset($user['details']) ? array_intersect_key($user['details'], array_flip([
                    'bio', 'designation', 'twitter', 'facebook', 'instagram', 'linkedin',
                ])) : null,
            ], fn($v) => $v !== null);
        }
        return $data;
    }
}
