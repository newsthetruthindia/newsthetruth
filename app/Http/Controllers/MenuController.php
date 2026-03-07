<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Page;
use Validator;

class MenuController extends Controller{

    public function getAll( Request $req ){
        $menu = Menu::orderBy('id', 'DESC')->paginate(10);
        return view('Menues.lists')->with(['menues'=>$menu]);
    }

    public function add( Request $req ){
        $data['menues']         = Menu::get();
        $data['pages']          = Page::get();
        $data['posts']          = Post::get();
        $data['categories']     = Category::get();
        $data['tags']           = Tag::get();
        return view('Menues.form')->with($data);
    }

    public function save( Request $req ){
        //dd($req);
        Validator::make($req->all(), [
            'title' => 'required|string|max:255',
        ])->validate();
        $id = $req->menu_id;
        $menu = Menu::where('id', $id )->first();
        //dd($menu);
        if( empty( $menu ) ){
            $menu = new Menu();
        }
        $menu->name = $req->title;
        $menu->save();
        if( !empty( $menu->id ) && !empty( $req->item ) ) {
            //dd( $req->item );
            $all_item = array_map(function($arr){ if( !empty($arr['item_id']) ) return $arr['item_id']; }, $req->item);
            MenuItem::where( 'menu_id', $menu->id )->whereNotIn('id', $all_item )->delete();
            foreach( $req->item as $k => $v ){
                $this->storeSubItem( $menu->id, $v );
            }
        }
        return redirect()->to(route('menus'));
    }

    private function storeSubItem( $parent_id, $data, $sub = false ){
        if( !empty( $parent_id ) && !empty( $data ) && !empty( $data['item_name'] ) && !empty( $data['item_link'] ) ){
            if( !empty( $data['item_id'] ) ){
                $submenu = MenuItem::where('id', $data['item_id'] )->first();
            }
            if( !isset( $submenu ) || empty( $submenu ) ){
                $submenu = new MenuItem();
            }
            if( $sub ){
                $submenu->menu_item_id = $parent_id;
            }else{
                $submenu->menu_id = $parent_id;
            }
            $submenu->display_name = $data['item_name'];
            $submenu->type = $data['item_type'];
            $submenu->slug = $data['item_link'];
            $submenu->target = !empty( $data['target'] ) ? $data['target'] : '_self';
            $submenu->save();
            if( !empty( $submenu->id ) && !empty( $data['item'] ) ){
                $old_item = array_map(function($arr){ if( $arr['item_id'] ) return $arr['item_id']; }, $data['item']);
                MenuItem::where( 'menu_item_id', $submenu->id )->whereNotIn('id', $old_item )->delete();
                foreach( $data['item'] as $k=> $v ){
                    $this->storeSubItem( $submenu->id, $v, true );
                }
            }
            return true;
        }
    }

    public function get( Request $req, Menu $menu ){
        $data['menu']           = $menu;
        $data['menues']         = Menu::get();
        $data['pages']          = Page::get();
        $data['posts']          = Post::get();
        $data['categories']     = Category::get();
        $data['tags']           = Tag::get();
        return view('Menues.form')->with($data);
    }

    public function delete( Request $req, Menu $menu ){
        //$menu->items()->subitems()->delete();
        $menu->items()->delete();
        $menu->delete();
        return redirect()->to(route('menus'));
    }
}
