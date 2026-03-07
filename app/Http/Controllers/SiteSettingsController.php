<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\Menu;
use Validator;
use Illuminate\Support\Facades\Auth;

class SiteSettingsController extends Controller{
    public function get(){
        $settings = SiteSetting::pluck('description','key')->toArray();
        $menus = Menu::get();
        return view('settings.sitesettings')->with(['settings'=>$settings,'menus'=>$menus]);
    }
    public static function check( $key='' ){
        if( !empty( $key ) ) return SiteSetting::where('key', $key)->first();
        return SiteSetting::pluck('description','key')->toArray();
    }
    public function save( Request $req ){
        $all_sets = !empty( $req->settings ) ? array_keys($req->settings) : [];
        SiteSetting::whereNotIn('key', $all_sets)->delete();
        if( !empty( $req->settings ) ){
            
            foreach( $req->settings as $metakey=>$metavalue ){
                //if(!empty( $metavalue ) ){
                    $user_ud = Auth::user()->id;
                    SiteSetting::updateOrCreate( [ 'key'=>$metakey], ['user_id'=>$user_ud, 'description'=> $metavalue] );
                //}
            }
        }
        return redirect()->to(route('settings-site'));
    }
}
