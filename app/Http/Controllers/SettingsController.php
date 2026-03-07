<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use Validator;
use Illuminate\Support\Facades\Auth;


class SettingsController extends Controller
{
    public function __construct(){
        $this->middleware(function ($request, $next) {
            if( empty(Auth::user()->google2fa_secret) ){
                return redirect('unauthenticated');
            }
            return $next($request);
        });
    }
    public function index(){

    }
    public function checkadmin(){
        echo 'hello';
        
    }


    public function getRoles( Request $request ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->view_settings ){
                return redirect('401');
            }
            $roles = UserRole::where('id', '!=', 0)->orderBy('id', 'DESC')->get();
            return view('settings.roles')->with(['roles'=>$roles]);
        }else{
            return redirect('401');
        }
    }

    public function getRoleForm( Request $request ){
        $form = view('settings.roleform')->render();;
        //echo $form;die;
        return response()->json(['form'=>$form ]);
    }

    public function getRole( Request $request, UserRole $UserRole ){

    }
    
    public function setRole( Request $request ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->update_settings ){
                return redirect('401');
            }
            Validator::make($request->all(), [
                'role_name' => 'required|max:255',
            ])->validate();
            $role_id = $request->role_id;
            if( !empty( $role_id ) ){
                $UserRole = UserRole::find($role_id);
            }else{
                $UserRole = new UserRole();
            }
            $UserRole->role_name =$request->role_name;

            if( Auth::user()->details->role->manage_user_settings ){
                $UserRole->create_user      = $request->create_user?$request->create_user:0;
                $UserRole->edit_user        = $request->edit_user ? $request->edit_user : 0;
                $UserRole->view_user        = $request->view_user ? $request->view_user : 0;
                $UserRole->delete_user      = $request->delete_user ? $request->delete_user : 0;
            }
            if( Auth::user()->details->role->manage_post_settings ){
                $UserRole->create_post                  =$request->create_post ? $request->create_post : 0;
                $UserRole->delete_post                  =$request->delete_post ? $request->delete_post : 0;
                $UserRole->edit_post                    =$request->edit_post ? $request->edit_post : 0;
                $UserRole->publish_post                 =$request->publish_post ? $request->publish_post : 0;
                $UserRole->review_post                  =$request->review_post ? $request->review_post : 0;
                $UserRole->set_post_priority            =$request->set_post_priority ? $request->set_post_priority : 0;
                $UserRole->update_post_seo              =$request->update_post_seo ? $request->update_post_seo : 0;
                $UserRole->edit_post_comment            =$request->edit_post_comment ? $request->edit_post_comment : 0;
                $UserRole->add_post_comment             =$request->add_post_comment ? $request->add_post_comment : 0;
                $UserRole->edit_others_post_comment     =$request->edit_others_post_comment ? $request->edit_others_post_comment : 0;
                $UserRole->delete_post_comment          =$request->delete_post_comment ? $request->delete_post_comment : 0;
                $UserRole->delete_others_post_comment   =$request->delete_others_post_comment ? $request->delete_others_post_comment : 0;
                $UserRole->create_page                  =$request->create_page ? $request->create_page : 0;
                $UserRole->view_page_list               =$request->view_page_list ? $request->view_page_list : 0;
                $UserRole->edit_page                    =$request->edit_page ? $request->edit_page : 0;
                $UserRole->delete_page                  =$request->delete_page ? $request->delete_page : 0;
            }
            $UserRole->create_gallery =$request->create_gallery ? $request->create_gallery : 0;
            $UserRole->update_gallery =$request->update_gallery ? $request->update_gallery : 0;
            $UserRole->delete_gallery =$request->delete_gallery ? $request->delete_gallery : 0;
            if( Auth::user()->details->role->manage_category_settings ){
                $UserRole->create_category  =$request->create_category ? $request->create_category : 0;
                $UserRole->edit_category    =$request->edit_category ? $request->edit_category : 0;
                $UserRole->delete_category  =$request->delete_category ? $request->delete_category : 0;
                $UserRole->create_tag       =$request->create_tag ? $request->create_tag : 0;
                $UserRole->edit_tag         =$request->edit_tag ? $request->edit_tag : 0;
                $UserRole->delete_tag       =$request->delete_tag ? $request->delete_tag : 0;
            }
            if( Auth::user()->details->role->manage_other_settings ){
                $UserRole->parmanant_delete             =$request->parmanant_delete ? $request->parmanant_delete : 0;
                $UserRole->manage_user_settings         =$request->manage_user_settings ? $request->manage_user_settings : 0;
                $UserRole->manage_site_settings         =$request->manage_site_settings ? $request->manage_site_settings : 0;
                $UserRole->manage_post_settings         =$request->manage_post_settings ? $request->manage_post_settings : 0;
                $UserRole->manage_category_settings     =$request->manage_category_settings ? $request->manage_category_settings : 0;
                $UserRole->manage_other_settings        =$request->manage_other_settings ? $request->manage_other_settings : 0;
                $UserRole->manage_menu                  =$request->manage_menu ? $request->manage_menu : 0;
            }

            $UserRole->save();
            if( !empty( $UserRole ) ){
                return response()->json(['UserRole'=>$UserRole ]);
            }else{
                return response()->json(['error'=>'true' ]);
            }
        }else{
            return redirect('401');
        }
        
    }
    
    public function updateRole( Request $request ){
        $id = $request->id;
        $UserRole = UserRole::find($id);
       // dd($UserRole);
        $form = view('settings.roleform')->with(['role'=>$UserRole])->render();;
        //echo $form;die;
        return response()->json(['form'=>$form ]);
    }
    
    public function deleteRole( Request $request ){
        $id = $request->id;
        $UserRole = UserRole::where('id', $id)->delete();
        if($UserRole){
            return response()->json(['error'=>false, 'msg'=>'Deleted Successfully' ]);
        }else{
            return response()->json(['error'=>true, 'msg'=>'Not Deleted' ]);
        }
    }
}
