<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\JustIn;
use App\Models\Page;
use App\Models\Postmeta;
use App\Models\SiteSetting;


class PublicPageController_v1 extends Controller
{
    public $settings;
    public function __construct()
    {
        $this->settings = SiteSetting::pluck('description', 'key')->toArray();
        /*$this->middleware(function ($request, $next) {
            if( empty(Auth::user()->google2fa_secret) ){
                return redirect()->to('https://www.newsthetruth.com');
            }
            return $next($request);
        });*/
    }

    public function handleRoute($x = '')
    {

        $path = explode('/', $x);
        // dd($path);
        if (empty($path)) {
            return $this->index();
        }
        $type = Page::where('slug', $path[0])->first();
        if (!empty($type)) {
            return $this->page($type);
        }
        $type = Post::where('slug', $path[0])->first();
        if (!empty($type)) {
            return $this->post($type);
        }
        $type = Category::where('slug', $path[0])->first();
        if (!empty($type)) {
            return $this->category($type);
        }
        $type = Tag::where('slug', $path[0])->first();
        if (!empty($type)) {
            return $this->tag($type);
        }
        $type = User::where('firstname', $path[0])->first();
        if (!empty($type)) {
            return $this->user();
        }
        return $this->notFound();
    }

    public function index($x = '')
    {
        // echo date('H:i:s');
        // die;
        $data['seo_data']['meta'] =  $this->settings;
        $logo = getAttachmentById($this->settings['site_logo']);
        $data['seo_data']['meta']['image'] =  !empty($logo) ? url($logo->url) : '';
        $data['seo_data']['meta']['type'] = 'website';
        $data['seo_data']['meta']['modified_time'] = '2024-03-08T19:02:51+00:00';
        $data['top_post'] = Post::where('status', 'published')
            ->where('visibility', 'public')
            ->where(function ($query) {
                $query->where('top_post', 1)
                    ->where('ignore_top_scheduling', '1')
                    ->orWhere([
                        ['top_post_to_time', '>', date('H:i:s')],
                        ['top_post_form_time', '<', date('H:i:s')],
                        ['post_publish_time', '<', date('Y-m-d H:i:s')]
                    ]);
            })
            ->limit(6)
            ->offset(0)
            ->orderBy('created_at', 'DESC')
            ->get();
        $data['the_latest'] = Post::where('status', 'published')->where('visibility', 'public')->limit(10)->offset(0)->orderBy('created_at', 'DESC')->get();
        $just_in = JustIn::orderBy('updated_at', 'DESC')->first();
        $just_in_parent = !empty($just_in->parent->id) ? $just_in->parent->id : '';
        $data['just_in'] = JustIn::where('just_in_id', $just_in_parent)->orWhere('id', $just_in_parent)->orWhere('id', $just_in->id)->orderBy('updated_at', 'DESC')->get();
        $data['just_ins'] = JustIn::limit(5)->offset(1)->orderBy('updated_at', 'DESC')->get();
        // $categories = ['15', '10', '11', '12', '13', '17', '21', '19', '22'];
        // $data['others'] = Post::where('status', 'published')
        //     ->where('visibility', 'public')
        //     ->whereHas('categories', function ($query) use ($categories) {
        //         $query->whereHas('cat_data', function ($q) use ($categories) {
        //             $q->whereIn('id', $categories);
        //         });
        //     })
        //     ->with(['categories' => function ($query) use ($categories) {
        //         $query->whereHas('cat_data', function ($q) use ($categories) {
        //             $q->whereIn('id', $categories);
        //         });
        //     }])
        //     ->orderBy('updated_at', 'DESC')
        //     ->get()
        //     ->groupBy(function ($post) {
        //         return $post->categories->first()->cat_data->title; // Assuming 'cat_data' has 'name'
        //     })->map(function ($groupedPosts) {
        //         return $groupedPosts->take(7); // Get the first 5 posts for each category
        //     });
        $categories = [15, 10, 11, 12, 13, 17, 21, 19, 22];
        $data['others'] = Post::where('status', 'published')
            ->where('visibility', 'public')
            ->whereHas('categories.cat_data', function ($q) use ($categories) {
                $q->whereIn('id', $categories);
            })
            ->with(['categories.cat_data' => function ($q) use ($categories) {
                $q->whereIn('id', $categories);
            }])
            ->orderByDesc('updated_at')
            ->limit(100) // Avoid pulling thousands of records
            ->get()
            ->groupBy(function ($post) {
                return optional(
                    optional($post->categories->first())->cat_data
                )->title ?? 'Uncategorized';
            })
            ->map(function ($posts) {
                return $posts->take(7);
            });
        $data['body_classes'] = 'home page-template-default page no-sidebar';
        $data['settings'] = $this->settings;
        return view('public-pages.home_v1')->with($data);
    }

    public function JustIn(Request $req, JustIn $id)
    {
        $meta = []; //array_column( $x->metas->toArray(),'description', 'key');
        $just_in_parent = !empty($id->parent->id) ? $id->parent->id : '';
        $data['just_in'] = JustIn::where('just_in_id', $just_in_parent)->orWhere('id', $just_in_parent)->orWhere('id', $id->id)->orderBy('updated_at', 'DESC')->get();
        $data['just_ins'] = JustIn::limit(10)->offset(1)->orderBy('updated_at', 'DESC')->get();
        $seo_data['meta'] = [];
        /*$seo_data['meta']['site_title'] = !empty( $meta['seo_title'] ) ? $meta['seo_title'] : $x->title;
        $seo_data['meta']['site_description'] = !empty( $meta['seo_description'] ) ? $meta['seo_description'] : $x->excerpt;
        $seo_data['meta']['image'] =  !empty( $x->thumbnails->url ) ? url($x->thumbnails->url) : '';
        $seo_data['meta']['type'] = 'article';
        $seo_data['meta']['modified_time'] = $x->updated_at;*/

        $body_classes = 'singular post-single page-template-default post no-sidebar';
        return view('public-pages.justin')->with(['data' => $data, 'seo_data' => $seo_data, 'meta' => $meta, 'body_classes' => $body_classes, 'settings' => $this->settings]);
    }

    public function post($x = [])
    {
        //dd(__DIR__);
        /*$client = new Google_Client();
        $client->setApplicationName('ntt-youtube-web-proj');
        $client->setScopes([
            'https://www.googleapis.com/auth/youtube.readonly',
        ]);
        $client->setAuthConfig('client_secret_352425922627-e89tlb12te0kjaad5u53sr09t31d373g.apps.googleusercontent.com.json');
        $client->setAccessType('offline');
        $client->setDeveloperKey('AIzaSyCF3m-2_Xa-KpP05snGi9vqaQeKNk69ALM');
        
        $service = new Google_Service_YouTube($client);
        
        
        $youtube_data = $service->search->listSearch('id, snippet', array(
            'channelId' => 'UCIWDXl6ONt_zAcCRviqu8bA',
            'order' => 'date',  // You can change the order as needed
            'type' => 'video',
            'maxResults' => 12,  // You can change the number of results as needed
        ));*/
        // echo 'postssss'; die;
        $youtube_data = [];
        $meta = array_column($x->metas->toArray(), 'description', 'key');
        $cats = $x->categories->pluck('category_id')->toArray();
        //dd($x->categories);
        $seo_data['meta'] = [];
        $seo_data['meta']['site_title'] = !empty($meta['seo_title']) ? $meta['seo_title'] : $x->title;
        $seo_data['meta']['site_description'] = !empty($meta['seo_description']) ? $meta['seo_description'] : $x->excerpt;
        $seo_data['meta']['image'] =  !empty($x->thumbnails->url) ? url($x->thumbnails->url) : '';
        $seo_data['meta']['type'] = 'article';
        $seo_data['meta']['modified_time'] = $x->updated_at;
        //dd($data['seo_data']);
        $just_ins = JustIn::limit(6)->offset(0)->orderBy('updated_at', 'DESC')->get();
        $top_post = Post::where('top_post', 1)->where('status', 'published')->where('visibility', 'public')->where('ignore_top_scheduling', '1')->orWhere([['top_post_to_time', '>', date('H:i:s')], ['top_post_form_time', '<', date('H:i:s')], ['post_publish_time', '<', date('Y-m-d H:i:s')]])->limit(5)->offset(0)->orderBy('updated_at', 'DESC')->get();
        $the_latest = Post::where('status', 'published')->where('visibility', 'public')->limit(16)->offset(0)->orderBy('updated_at', 'DESC')->get();
        $similars = Post::where('status', 'published')->where('visibility', 'public')->whereHas('categories', function ($query) use ($cats) {
            $query->whereHas('cat_data', function ($q) use ($cats) {
                $q->whereIn('id', $cats);
            });
        })->limit(8)->offset(0)->orderBy('updated_at', 'DESC')->get();
        $body_classes = 'singular post-single page-template-default post no-sidebar';
        $data = [
            'the_post' => $x,
            'seo_data' => $seo_data,
            'meta' => $meta,
            'top_post' => $top_post,
            'just_ins' => $just_ins,
            'the_latest' => $the_latest,
            'similars' => $similars,
            'body_classes' => $body_classes,
            'settings' => $this->settings,
            'youtube_data' => $youtube_data
        ];
        // return view('public-pages.post')->with($data);
        return view('public-pages.post_v1')->with($data);
    }

    public function page($x = '')
    {
        // echo 'Here2'; die;
        $body_classes = 'home page-template-default page no-sidebar';
        $meta = array_column($x->metas->toArray(), 'description', 'key');
        $seo_data['meta'] = [];
        $seo_data['meta']['site_title'] = !empty($meta['seo_title']) ? $meta['seo_title'] : $x->title;
        $seo_data['meta']['site_description'] = !empty($meta['seo_description']) ? $meta['seo_description'] : $x->excerpt;
        $seo_data['meta']['image'] =  !empty($x->thumbnails->url) ? url($x->thumbnails->url) : '';
        $seo_data['meta']['type'] = 'article';
        $seo_data['meta']['modified_time'] = $x->updated_at;
        $data = [
            'the_page' => $x,
            'body_classes' => $body_classes,
            'settings' => $this->settings,
            'seo_data' => $seo_data
        ];
        // return view('public-pages.page')->with($data);
        return view('public-pages.page_v1')->with($data);
    }

    public function category($x = '')
    {
        $posts = Post::where('status', 'published')->where('visibility', 'public')->whereHas('categories', function ($query) use ($x) {
            $query->whereHas('cat_data', function ($q) use ($x) {
                $q->where('id', $x->id);
            });
        })->orderBy('updated_at', 'DESC');
        $meta = array_column($x->metas->toArray(), 'description', 'key');
        $seo_data['meta'] = [];
        $seo_data['meta']['site_title'] = !empty($meta['seo_title']) ? $meta['seo_title'] : $x->title;
        $seo_data['meta']['site_description'] = !empty($meta['seo_description']) ? $meta['seo_description'] : $x->excerpt;
        $seo_data['meta']['image'] =  !empty($x->thumbnails->url) ? url($x->thumbnails->url) : '';
        $seo_data['meta']['type'] = 'article';
        $seo_data['meta']['modified_time'] = $x->updated_at;
        $the_archive = $posts->paginate(20);
        $top_post = Post::where('top_post', 1)->where('status', 'published')->where('visibility', 'public')->where('ignore_top_scheduling', '1')->orWhere([['top_post_to_time', '>', date('H:i:s')], ['top_post_form_time', '<', date('H:i:s')], ['post_publish_time', '<', date('Y-m-d H:i:s')]])->limit(5)->offset(0)->orderBy('updated_at', 'DESC')->get();
        $the_latest = Post::where('status', 'published')->where('visibility', 'public')->limit(5)->offset(0)->orderBy('updated_at', 'DESC')->get();
        $categories = Category::get();
        $body_classes = 'archive page-template-default term-page category-taxonomy no-sidebar';
        $data = [
            'the_archive' => $x,
            'posts' => $the_archive,
            'seo_data' => $seo_data,
            'top_post' => $top_post,
            'the_latest' => $the_latest,
            'categories' => $categories,
            'cat_data' => $x,
            'body_classes' => $body_classes,
            'settings' => $this->settings
        ];
        // return view('public-pages.category')->with($data);
        return view('public-pages.category_v1')->with($data);
    }
    public function archive($x = '')
    {
        $categories = Category::get();

        $seo_data['meta'] = [];
        $seo_data['meta']['site_title'] = 'Seach all category in News The Truth';
        $seo_data['meta']['site_description'] = 'News the truth searches for all category';
        $seo_data['meta']['image'] =  !empty($x->thumbnails->url) ? url($x->thumbnails->url) : '';
        $seo_data['meta']['type'] = 'article';
        $seo_data['meta']['modified_time'] = date('Y-m-d G:i:s');
        $body_classes = 'archive page-template-default page no-sidebar';
        return view('public-pages.archive')->with(['categories' => $categories, 'body_classes' => $body_classes, 'settings' => $this->settings, 'seo_data' => $seo_data]);
    }
    public function latest($x = '')
    {
        $posts = $data['the_latest'] = Post::where('status', 'published')->where('visibility', 'public')->orderBy('updated_at', 'DESC')->paginate(10);
        $body_classes = 'archive page-template-default term-page category-taxonomy no-sidebar';
        return view('public-pages.posts')->with(['posts' => $posts, 'body_classes' => $body_classes, 'settings' => $this->settings]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $posts = Post::where('status', 'published')
            ->where('visibility', 'public')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%");
            })
            ->orderBy('updated_at', 'DESC')
            ->paginate(20);

        $categories = Category::get();
        $body_classes = 'archive page-template-default term-page category-taxonomy no-sidebar';
        return view('public-pages.search_v1')->with([
            'posts' => $posts,
            'search_query' => $query,
            'categories' => $categories,
            'body_classes' => $body_classes,
            'settings' => $this->settings
        ]);
    }

    public function tag($x = '')
    {
        $posts = Post::where('status', 'published')->where('visibility', 'public')->whereHas('tags', function ($query) use ($x) {
            $query->whereHas('tag_data', function ($q) use ($x) {
                $q->where('id', $x->id);
            });
        })->orderBy('updated_at', 'DESC');
        $meta = !empty($x->metas) ? array_column($x->metas->toArray(), 'description', 'key') : [];
        $seo_data['meta'] = [];
        $seo_data['meta']['site_title'] = !empty($meta['seo_title']) ? $meta['seo_title'] : $x->title;
        $seo_data['meta']['site_description'] = !empty($meta['seo_description']) ? $meta['seo_description'] : $x->excerpt;
        $seo_data['meta']['image'] =  !empty($x->thumbnails->url) ? url($x->thumbnails->url) : '';
        $seo_data['meta']['type'] = 'article';
        $seo_data['meta']['modified_time'] = $x->updated_at;
        $the_archive = $posts->paginate(20);
        $top_post = Post::where('status', 'published')
            ->where('visibility', 'public')
            ->where(function ($query) {
                $query->where('top_post', 1)
                    ->where('ignore_top_scheduling', '1')
                    ->orWhere([
                        ['top_post_to_time', '>', date('H:i:s')],
                        ['top_post_form_time', '<', date('H:i:s')],
                        ['post_publish_time', '<', date('Y-m-d H:i:s')]
                    ]);
            })
            ->limit(5)
            ->offset(0)
            ->orderBy('updated_at', 'DESC')
            ->get();
        $the_latest = Post::where('status', 'published')->where('visibility', 'public')->limit(5)->offset(0)->orderBy('updated_at', 'DESC')->get();
        $tags = Tag::get();
        $body_classes = 'archive page-template-default term-page category-taxonomy no-sidebar';
        // return view('public-pages.tag')->with(['the_archive' => $x, 'posts' => $the_archive, 'seo_data' => $seo_data, 'top_post' => $top_post, 'the_latest' => $the_latest, 'tags' => $tags, 'cat_data' => $x, 'body_classes' => $body_classes, 'settings' => $this->settings]);
        return view('public-pages.tag_v1')->with(['the_archive' => $x, 'posts' => $the_archive, 'seo_data' => $seo_data, 'top_post' => $top_post, 'the_latest' => $the_latest, 'tags' => $tags, 'cat_data' => $x, 'body_classes' => $body_classes, 'settings' => $this->settings]);
    }

    public function user($x = '')
    {
        echo 'this is user page';
    }

    public function unAuthorized()
    {
        return view('unauthorized');
    }

    public function notFound($x = '')
    {
        return view('not_found');
    }
}
