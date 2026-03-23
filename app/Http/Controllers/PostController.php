<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Postmeta;
use App\Models\postCategory;
use App\Models\postTag;
use App\Models\PostGallery;
use Validator;
use Illuminate\Support\Facades\Http;

class PostController extends Controller{
    
    public function __construct(){
        $this->middleware(function ($request, $next) {
            if( empty(Auth::user()->google2fa_secret) ){
                return redirect('unauthenticated');
            }
            return $next($request);
        });
    }

    public function list(Request $req){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->view_post_list ){
                return redirect('401');
            }
            
            $posts = Post::orderBy('id', 'DESC');
            if( !empty($req->title)){
                $posts->where('title', 'like', '%' . $req->title . '%');
            }
            if( !empty($req->start_date)){
                $posts->whereDate('updated_at', '>', $req->start_date );
            }
            if( !empty($req->end_date)){
                $posts->whereDate('updated_at', '<', $req->end_date );
            }
            $posts=$posts->paginate(10)->appends($req->only(['title', 'start_date', 'end_date']));
            //dd($posts);
            return view('Posts.lists')->with(['posts'=>$posts, 'params'=>$req->all()]);
        }else{
            return redirect('401');
        }
    }

    public function listJson(Request $req){
        $posts = Post::orderBy('id', 'DESC')->get();
        return json_encode(['posts'=>$posts]);
    }

    public function add( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->create_post ){
                return redirect('401');
            }
            $categories = Category::orderBy('id', 'DESC')->get();
            $tags = Tag::orderBy('id', 'DESC')->get();
            return view('Posts.form')->with(['categories'=>$categories, 'tags'=>$tags]);
        }else{
            return redirect('401');
        }
    }

    public function addQuick( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->create_post ){
                return redirect('401');
            }
            $categories = Category::orderBy('id', 'DESC')->get();
            $tags = Tag::orderBy('id', 'DESC')->get();
            return view('Posts.quick-form')->with(['categories'=>$categories, 'tags'=>$tags]);
        }else{
            return redirect('401');
        }
    }

    public function save( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            $id = $req->post_id;
            if( !empty( $id ) ){
                if( !Auth::user()->details->role->edit_post ){
                    return redirect('401');
                }
            }else{
                if( !Auth::user()->details->role->create_post ){
                    return redirect('401');
                }
            }            
            Validator::make($req->all(), [
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:posts,slug,'.$id,
                'subtitle' => 'nullable|string|max:255',
                'excerpt' => 'nullable|string|regex:/^(\s*\S+\s*){1,30}$/',
                'description' => 'required',
                'thumbnail' => 'required',
                'post_gallery' => 'required',
            ])->validate();
            
            $post = Post::where('id', $id )->first();
            if( empty( $post ) ){
                $post = new Post();
                $post->user_id = Auth::user()->id;
            }
            if( !empty($req->mark_as_reviewed) && $req->mark_as_reviewed == 'on' ){
                if( !Auth::user()->details->role->review_post ){
                    return redirect('401');
                }
                $post->reviewed_by=Auth::user()->id;
            }
            if( !empty($req->status) && $req->status == 'published' ){
                if( !Auth::user()->details->role->publish_post ){
                    return redirect('401');
                }
                $post->published_by=Auth::user()->id;
            }
            $post->title = $req->title;
            $post->slug = $req->slug;
            $post->subtitle = $req->subtitle;
            $post->description = $req->description;
            $post->excerpt = !empty( $req->excerpt )? $req->excerpt :implode(' ', array_slice(explode(' ', strip_tags( $req->description ) ), 0, 30));
            $post->post_of_the_day = $req->post_of_the_day;
            $post->top_post = $req->mark_as_top && $req->mark_as_top == 'on'?1:0;
            $post->video_url = $req->video_url;
            $post->thumbnail = $req->thumbnail;
            $post->top_post_to_time = !empty( $req->to_time ) ? date('H:i:s', strtotime( $req->to_time )) : null;
            $post->top_post_form_time =  !empty( $req->from_time ) ? date('H:i:s', strtotime( $req->from_time )) : null;
            $post->status = $req->status;
            $post->visibility = $req->visibility;
            $post->post_publish_time = !empty( $req->publish_date ) ? date('Y-m-d', strtotime( $req->publish_date )) : date('Y-m-d');
            $post->ignore_top_scheduling = $req->avoid_scheduling && $req->avoid_scheduling =='on'?1:0;
            try{
                $post->save();
            }catch(Exception $e){
                dd($e);
            }
            if( !empty( $post->id ) && !empty( $req->postmeta ) ){
                foreach( $req->postmeta as $metakey=>$metavalue ){
                    if(!empty( $metavalue ) ){
                        $user_ud = Auth::user()->id;
                        Postmeta::updateOrCreate( [ 'key'=>$metakey, 'post_id'=>$post->id ], ['user_id'=>$user_ud, 'description'=> $metavalue] );
                    }else{
                        Postmeta::where('key', $metakey)->where('post_id', $post->id )->delete();
                    }
                }
            }
            //dd($req->category);
            //dd($req->tags);
            $all_cats = !empty( $req->category ) ? $req->category : [];
            $all_tags = !empty( $req->tags ) ? $req->tags : [];
            $all_post_gallery = !empty( $req->post_gallery ) ? $req->post_gallery : [];
            //dd($all_post_gallery);
            if( !empty( $post->id ) ){
                postTag::where( 'post_id', $post->id )->whereNotIn('tag_id', $all_tags )->delete();
                postCategory::where( 'post_id', $post->id )->whereNotIn('category_id', $all_cats )->delete();
                PostGallery::where( 'post_id', $post->id )->whereNotIn('media_id', $all_cats )->delete();
                if( !empty( $all_cats ) ){
                    foreach( $all_cats as $cat ){
                        if( !empty( $cat ) ){
                            postCategory::firstOrCreate( [ 'post_id'=>$post->id, 'category_id'=>$cat] );
                        }
                    }
                }
                if( !empty( $all_tags ) ){
                    foreach( $all_tags as $tag ){
                        if( !empty( $tag ) ){
                            postTag::firstOrCreate( [ 'post_id'=>$post->id, 'tag_id'=>$tag] );
                        }
                    }
                }
                if( !empty( $all_post_gallery ) ){
                    foreach( $all_post_gallery as $gal ){
                        if( !empty( $gal ) ){
                            PostGallery::firstOrCreate( [ 'post_id'=>$post->id, 'media_id'=>$gal] );
                        }
                    }
                }
            }
            $notification = new NotificationController();
            $notification->description('A post has been updated');
            $notification->type('users');
            $notification->send();
            return redirect()->to( route( 'post-edit', [ 'post'=>$post->id] ));
        }else{
            return redirect('401');
        }
    }

    public function quickSave( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            $id = $req->post_id;
            if( !empty( $id ) ){
                if( !Auth::user()->details->role->edit_post ){
                    return redirect('401');
                }
            }else{
                if( !Auth::user()->details->role->create_post ){
                    return redirect('401');
                }
            }            
            Validator::make($req->all(), [
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'excerpt' => 'nullable|string|regex:/^(\s*\S+\s*){1,30}$/',
                'description' => 'required',
                'thumbnail' => 'required',
                'post_gallery' => 'required',
            ])->validate();
            
            $post = Post::where('id', $id )->first();
            if( empty( $post ) ){
                $post = new Post();
                $post->user_id = Auth::user()->id;
            }
            $post->title = $req->title;
            $post->slug = $req->slug;
            $post->subtitle = $req->subtitle;
            $post->description = $req->description;
            $post->excerpt = !empty( $req->excerpt )? $req->excerpt :implode(' ', array_slice(explode(' ', strip_tags( $req->description ) ), 0, 30));
            $post->video_url = $req->video_url;
            $post->thumbnail = $req->thumbnail;
            $post->post_publish_time = !empty( $req->publish_date ) ? date('Y-m-d', strtotime( $req->publish_date )) : date('Y-m-d');
            try{
                $post->save();
            }catch(Exception $e){
                dd($e);
            }
            
            $all_cats = !empty( $req->category ) ? $req->category : [];
            $all_tags = !empty( $req->tags ) ? $req->tags : [];
            $all_post_gallery = !empty( $req->post_gallery ) ? $req->post_gallery : [];
            //dd($all_post_gallery);
            if( !empty( $post->id ) ){
                postTag::where( 'post_id', $post->id )->whereNotIn('tag_id', $all_tags )->delete();
                postCategory::where( 'post_id', $post->id )->whereNotIn('category_id', $all_cats )->delete();
                PostGallery::where( 'post_id', $post->id )->whereNotIn('media_id', $all_cats )->delete();
                if( !empty( $all_cats ) ){
                    foreach( $all_cats as $cat ){
                        if( !empty( $cat ) ){
                            postCategory::firstOrCreate( [ 'post_id'=>$post->id, 'category_id'=>$cat] );
                        }
                    }
                }
                if( !empty( $all_tags ) ){
                    foreach( $all_tags as $tag ){
                        if( !empty( $tag ) ){
                            postTag::firstOrCreate( [ 'post_id'=>$post->id, 'tag_id'=>$tag] );
                        }
                    }
                }
                if( !empty( $all_post_gallery ) ){
                    foreach( $all_post_gallery as $gal ){
                        if( !empty( $gal ) ){
                            PostGallery::firstOrCreate( [ 'post_id'=>$post->id, 'media_id'=>$gal] );
                        }
                    }
                }
            }
            $notification = new NotificationController();
            $notification->description('A post has been updated');
            $notification->type('users');
            $notification->send();
            return redirect()->to(route('quick-post'));
        }else{
            return redirect('401');
        }
    }

    public function updatePostSEO( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->update_post_seo ){
                return redirect('401');
            }
            $id = $req->post_id;
            $post = Post::where('id', $id )->first();
            
            if( !empty( $post->id ) && !empty( $req->postmeta ) ){
                foreach( $req->postmeta as $metakey=>$metavalue ){
                    if(!empty( $metavalue ) ){
                        $user_ud = Auth::user()->id;
                        Postmeta::updateOrCreate( [ 'key'=>$metakey, 'post_id'=>$post->id ], ['user_id'=>$user_ud, 'description'=> $metavalue] );
                    }
                }
            }
            $all_post = Post::paginate(10);
            $the_page_no = !empty( $req->page_no ) && $req->page_no <= $all_post->lastPage() ? $req->page_no : $all_post->lastPage();
            return redirect()->to( route( 'posts', ['page'=> $the_page_no]) );
        }else{
            return redirect('401');
        }
    }

    public function edit( Request $req, Post $post ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->edit_post ){
                return redirect('401');
            }
            $categories = Category::get();
            $tags = Tag::get();
            $data = $post;
            $meta = array_column( $post->metas->toArray(),'description', 'key');
            return view('Posts.form')->with(['categories'=>$categories, 'tags'=>$tags, 'data'=>$data, 'meta'=>$meta]);
        }else{
            return redirect('401');
        }
    }

    public function editSeo( Request $req, Post $post ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->update_post_seo ){
                return redirect('401');
            }
            $categories = Category::get();
            $tags = Tag::get();
            $data = $post;
            $meta = array_column( $post->metas->toArray(),'description', 'key');
            
            return view('Posts.review-form')->with(['categories'=>$categories, 'tags'=>$tags, 'data'=>$data, 'meta'=>$meta, 'page_no'=>$req->page_no]);
        }else{
            return redirect('401');
        }
    }

    public function delete( Request $req, Post $post ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->delete_post ){
                return redirect('401');
            }
            $post->delete();
            $all_post = Post::paginate(10);
            $the_page_no = !empty( $req->page_no ) && $req->page_no <= $all_post->lastPage() ? $req->page_no : $all_post->lastPage();
            return redirect()->to( route( 'posts', ['page'=> $the_page_no]) );
        }else{
            return redirect('401');
        }
    }

    public function deletePermanant( Request $req, Post $post ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->parmanant_delete ){
                return redirect('401');
            }
            $post->forceDelete();
            $all_post = Post::paginate(10);
            $the_page_no = !empty( $req->page_no ) && $req->page_no <= $all_post->lastPage() ? $req->page_no : $all_post->lastPage();
            return redirect()->to( route( 'posts', ['page'=> $the_page_no]) );
        }else{
            return redirect('401');
        }
    }

    public function updatePostAudio( Request $req ){
        $text       = $req->text;
        $type       = $req->type;
        $post_id    = $req->post_id;
        $post       = Post::where( 'id', $post_id )->first();
        if( !empty( $text ) && !empty( $post ) ){
            $name = $this->convertAudio( htmlspecialchars_decode($text), $type, $post->slug );
            if( !empty( $name ) ){
                $post->audio_clip_url = url('/public/audios/'.$name);
                $post->save();
                return $post;
            }
        }
        return $post;
    }

    public function convertAudio($text='', $type='text', $slug='')
    {
        try {
            $slug = empty($slug) ? time() : $slug;
            $name = $slug . '.mp3';

            $response = Http::post('https://texttospeech.googleapis.com/v1/text:synthesize?key=' . config('services.google_cloud.key'), [
                'input' => ['text' => $text],
                'voice' => [
                    'languageCode' => 'en-IN',
                    'name' => 'en-IN-Neural2-B'
                ],
                'audioConfig' => [
                    'audioEncoding' => 'MP3',
                    'pitch' => -2.0,
                    'speakingRate' => 1.05
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('TTS API failed: ' . $response->body());
            }

            $audioContent = base64_decode($response->json('audioContent'));
            $path = public_path('audios');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            file_put_contents($path . '/' . $name, $audioContent);
            return $name;
        } catch (\Exception $e) {
            \Log::error('TTS Error: ' . $e->getMessage());
            return null;
        }
    }
}
