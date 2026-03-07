<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tag;
use Validator;

class TagController extends Controller{
    public function list(Request $req){
        $tags = Tag::orderBy('id', 'DESC')->paginate(10);
        return view('Tag.lists')->with(['tags'=>$tags]);
    }

    public function listJson(Request $req){
        $tags = Tag::orderBy('id', 'DESC')->get();
        return json_encode(['tags'=>$tags]);
    }

    public function add( Request $req ){
       
        return view('Tag.form');
    }

    public function save( Request $req ){
        $id = $req->tag_id;
       Validator::make($req->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,'.$id,
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required',
        ])->validate();
        //dd( $req );
        
        $cat = Tag::where('id', $id )->first();
        if( empty( $cat ) ){
            $cat = new Tag();
        }
        $cat->title = $req->title;
        $cat->slug = $req->slug;
        $cat->subtitle = $req->subtitle;
        $cat->description = $req->description;
        $cat->primary_menu_order = $req->primary_menu_order;
        $cat->secondary_menu_order = $req->secondary_menu_order;
        $cat->footer_menu_order = $req->footer_menu_order;
        $cat->user_id = Auth::user()->id;
        $cat->save();
        return redirect()->to('admin/tag/list');
    }

    public function edit( Request $req, Tag $tag ){
        //dd($cat);
        $tags = Tag::whereNot('id', $tag->id)->orderBy('id', 'DESC')->get();
        $data = $tag;
        return view('Tag.form')->with(['tags'=>$tags, 'data'=>$data]);
    }

    public function delete( Request $req, Tag $tag ){
        $tag->delete();
        return redirect()->to('admin/tag/list');
    }
}
