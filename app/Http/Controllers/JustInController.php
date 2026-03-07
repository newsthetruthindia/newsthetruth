<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\JustIn;
use Validator;

class JustInController extends Controller {
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
            $posts = JustIn::orderBy('id', 'DESC')->paginate(10);
            return view('Justin.lists')->with(['posts'=>$posts]);
        }else{
            return redirect('401');
        }
    }

    public function listJson(Request $req){
        $posts = JustIn::orderBy('id', 'DESC')->get();
        return json_encode(['posts'=>$posts]);
    }

    public function add( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->create_post ){
                return redirect('401');
            }
            $posts = JustIn::orderBy('id', 'DESC')->get();
            return view('Justin.form')->with(['posts'=>$posts]);
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
                'description' => 'required',
                'thumbnail' => 'required',
            ])->validate();
            
            $post = JustIn::where('id', $id )->first();
            if( empty( $post ) ){
                $post = new JustIn();
                $post->user_id = Auth::user()->id;
            }
            
            $post->title = !empty( $req->title ) ? $req->title : '';
            $post->slug = !empty( $req->slug ) ? $req->slug : '';
            $post->description = $req->description;
            $post->just_in_id = $req->parent;
            $post->excerpt = !empty( $req->excerpt )? $req->excerpt :implode(' ', array_slice(explode(' ', strip_tags( $req->description ) ), 0, 30));
           
            $post->thumbnail = $req->thumbnail;
           
            try{
                $post->save();
            }catch(Exception $e){
                dd($e);
            }
            return redirect()->to(route('justins'));
        }else{
            return redirect('401');
        }
    }


    public function edit( Request $req, JustIn $post ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->edit_post ){
                return redirect('401');
            }
            $data = $post;
            $posts = JustIn::orderBy('id', 'DESC')->get();
            return view('Justin.form')->with(['data'=>$data, 'posts'=>$posts]);
        }else{
            return redirect('401');
        }
    }

   

    public function delete( Request $req, JustIn $post ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->delete_post ){
                return redirect('401');
            }
            $post->delete();
            return redirect()->to(route('justins'));
        }else{
            return redirect('401');
        }
    }

    public function deletePermanant( Request $req, JustIn $post ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->parmanant_delete ){
                return redirect('401');
            }
            $post->forceDelete();
            return redirect()->to(route('justins'));
        }else{
            return redirect('401');
        }
    }

   
}
