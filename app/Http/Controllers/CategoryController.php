<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\CategoryMeta;
use Validator;

class CategoryController extends Controller{

    public function __construct(){
        $this->middleware(function ($request, $next) {
            if( empty(Auth::user()->google2fa_secret) ){
                return redirect('unauthenticated');
            }
            return $next($request);
        });
    }

    public function list(Request $req){
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return view('Category.lists')->with(['categories'=>$categories]);
    }

    public function listJson(Request $req){
        $categories = Category::orderBy('id', 'DESC')->get();
        return json_encode(['cats'=>$categories]);
    }

    public function add( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->create_category ){
                return redirect('401');
            }
            $categories = Category::orderBy('id', 'DESC')->get();
            return view('Category.form')->with(['categories'=>$categories]);
        }else{
            return redirect('401');
        }
    }

    public function save( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            $id = $req->cat_id;
            if( !empty( $id ) ){
                if( !Auth::user()->details->role->edit_category ){
                    return redirect('401');
                }
            }else{
                if( !Auth::user()->details->role->create_category ){
                    return redirect('401');
                }
            }
            Validator::make($req->all(), [
                'title' => 'required|string|max:255|unique:categories,title,'.$id,
                'slug' => 'required|string|max:255|unique:categories,slug,'.$id,
                'subtitle' => 'nullable|string|max:255',
                'description' => 'required',
                'parent' => 'nullable|numeric',
            ])->validate();
            $cat = Category::where('id', $id )->first();
            //dd($req->firstname);
            if( empty( $cat ) ){
                $cat = new Category();
                $cat->user_id = Auth::user()->id;
            }
            $cat->title = $req->title;
            $cat->slug = $req->slug;
            $cat->subtitle = $req->subtitle;
            $cat->description = $req->description;
            $cat->thumbnail = $req->thumbnail;
            $cat->category_id = $req->parent;
            $cat->primary_menu_order = $req->primary_menu_order;
            $cat->secondary_menu_order = $req->secondary_menu_order;
            $cat->footer_menu_order = $req->footer_menu_order;
            $cat->save();
            if( !empty( $cat->id ) && !empty( $req->catmeta ) ){
                foreach( $req->catmeta as $metakey=>$metavalue ){
                    //if(!empty( $metavalue ) ){
                        $user_ud = Auth::user()->id;
                        CategoryMeta::updateOrCreate( [ 'key'=>$metakey, 'category_id'=>$cat->id ], ['user_id'=>$user_ud, 'description'=> $metavalue] );
                    //}
                }
            }
            return redirect()->to('admin/category/list');
        }else{
            return redirect('401');
        }
    }

    public function edit( Request $req, Category $cat ){
        
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->edit_category ){
                return redirect('401');
            }
            $categories = Category::whereNot('id', $cat->id)->get();
            $data = $cat;
            $meta = array_column( $cat->metas->toArray(),'description', 'key');
            return view('Category.form')->with(['categories'=>$categories, 'data'=>$data, 'meta'=>$meta]);
        }else{
            return redirect('401');
        }
    }

    public function delete( Request $req, Category $cat ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->delete_category ){
                return redirect('401');
            }
            $cat->delete();
            return redirect()->to('admin/category/list');
        }else{
            return redirect('401');
        }
    }
}
