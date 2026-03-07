<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Page;
use App\Models\pageMeta;
use Validator;

class PageController extends Controller{
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
            if( !Auth::user()->details->role->view_page_list ){
                return redirect('401');
            }
            $posts = Page::orderBy('id', 'DESC')->paginate(10);
            return view('Page.lists')->with(['posts'=>$posts]);
        }else{
            return redirect('401');
        }
    }

    public function listJson(Request $req){
        $posts = Page::orderBy('id', 'DESC')->get();
        return json_encode(['pages'=>$posts]);
    }

    public function add( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->create_page ){
                return redirect('401');
            }
            $categories = Category::orderBy('id', 'DESC')->get();
            $tags = Tag::orderBy('id', 'DESC')->get();
            return view('Page.form')->with(['categories'=>$categories, 'tags'=>$tags]);
        }else{
            return redirect('401');
        }
    }

    public function save( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            $id = $req->page_id;
            if( !empty( $id ) ){
                if( !Auth::user()->details->role->edit_page ){
                    return redirect('401');
                }
            }else{
                if( !Auth::user()->details->role->create_page ){
                    return redirect('401');
                }
            }
            Validator::make($req->all(), [
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:pages,slug,'.$id,
                'subtitle' => 'nullable|string|max:255',
                'description' => 'required',//
            ])->validate();
            $post = Page::where('id', $id )->first();
            if( empty( $post ) ){
                $post = new Page();
            }
            $post->title = $req->title;
            $post->slug = $req->slug;
            $post->subtitle = $req->subtitle;
            $post->description = $req->description;
            $post->status = $req->status;
            $post->visibility = $req->visibility;
            $post->post_publish_time = !empty( $req->publish_date ) ? date('Y-m-d', strtotime( $req->publish_date )) : date('Y-m-d');
            $post->user_id = Auth::user()->id;
            try{
                $post->save();
            }catch(Exception $e){
                dd($e);
            }
            if( !empty( $post->id ) && !empty( $req->postmeta ) ){
                foreach( $req->postmeta as $metakey=>$metavalue ){
                    $user_ud = Auth::user()->id;
                    pageMeta::updateOrCreate( [ 'key'=>$metakey, 'post_id'=>$post->id ], ['user_id'=>$user_ud, 'description'=> $metavalue] );
                }
            }
            $notification = new NotificationController();
            $notification->description('A page has been updated');
            $notification->type('users');
            $notification->send();
            return redirect()->to('admin/page/list');
        }else{
            return redirect('401');
        }
    }

    public function edit( Request $req, Page $post ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->edit_page ){
                return redirect('401');
            }
            $categories = Category::orderBy('id', 'DESC')->get();
            $tags = Tag::orderBy('id', 'DESC')->get();
            $data = $post;
            $meta = array_column( $post->metas->toArray(),'description', 'key');
            return view('Page.form')->with(['categories'=>$categories, 'tags'=>$tags, 'data'=>$data, 'meta'=>$meta]);
        }else{
            return redirect('401');
        }
    }

    public function delete( Request $req, Page $post ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->delete_page ){
                return redirect('401');
            }
            $post->delete();
            return redirect()->to('admin/page/list');
        }else{
            return redirect('401');
        }
    }}
