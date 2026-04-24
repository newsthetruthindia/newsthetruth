<?php

namespace App\Http\Controllers;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserSetting;
use App\Models\UserRole;
use App\Models\Media;
use Illuminate\Support\Facades\Mail;
use App\Mail\googleAuthenticator;
use App\Models\ProjectMember;
use App\Models\TaskMember;
use App\Models\Project;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Mail\PasswordForgotMail;
use App\Mail\AccountVerificationMail;
class UserController extends Controller{
    public function __construct(){
        $this->middleware(function ($request, $next) {
            if( empty(Auth::user()->google2fa_secret) ){
                return redirect('unauthenticated');
            }
            return $next($request);
        });
    }

    public function getUserList( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->view_user ){
                return redirect('401');
            }
            $users = User::where('type', ['admin', 'employee'])->whereNot('id', '0')->withTrashed()->orderBy('id', 'DESC')->paginate(10);
            return view('users.lists')->with(['users'=>$users]);
        }else{
            return redirect('401');
        }
    }

    public function getSubscriberList( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->view_user ){
                return redirect('401');
            }
            $users = User::where('type', 'user')->whereNot('id', '0')->withTrashed()->orderBy('id', 'DESC')->paginate(10);
            return view('users.lists')->with(['users'=>$users]);
        }else{
            return redirect('401');
        }
    }

    public function userProfile( Request $request, User $id ){
        $user = $id;
        //dd($user->details);
        return view('users.profile')->with(['user'=>$user]);
    }

    public function editUser( Request $request, User $id ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->edit_user ){
                return redirect('401');
            }
            $user = $id;
            $roles = UserRole::orderBy('id', 'DESC')->get();
            return view('users.form')->with(['user'=>$user, 'roles'=>$roles]);
        }else{
            return redirect('401');
        }
    }

    public function addUser( Request $request ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->create_user ){
                return redirect('401');
            }
            $roles = UserRole::orderBy('id', 'DESC')->get();
            return view('users.form')->with(['roles'=>$roles]);
        }else{
            return redirect('401');
        }
    }

    public function deleteUserForce( Request $request, $id ){
        //dd($id);
        $user = User::where('id', $id)->withTrashed()->first();
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->parmanant_delete ){
                return redirect('401');
            }
            $user->forceDelete();
            return redirect()->to('user-list');
        }else{
            return redirect('401');
        }
    }

    public function deleteUser( Request $request, $id ){
        //dd($id);
        $user = User::where('id', $id)->withTrashed()->first();
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->delete_user ){
                return redirect('401');
            }
            $user->delete();
            return redirect()->to('user-list');
        }else{
            return redirect('401');
        }
    }

    public function deleteAccount( Request $request, User $id ){
        $id->delete();
        return redirect()->to('login');
    }

    public function saveUser( Request $req ){
        if( !empty( Auth::user()->details->role ) ){
            if( !Auth::user()->details->role->edit_user && !Auth::user()->details->role->create_user ){
                return redirect('401');
            }
            Validator::make($req->all(), [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required_if:user_id,==,null|nullable|string|email|max:255|unique:users',
                'phone' => 'nullable|numeric',
                'role_id' => 'required',
                'password' => 'required_if:user_id,==,null|nullable',
                'c_passwpord' => 'nullable|same:password',
            ])->validate();
            //dd( $req );
            $id = $req->user_id;
            $user = User::where('id', $id )->first();
            //dd($req->firstname);
            if( empty( $user ) ){
                $user = new User();
            }
            $user->firstname = $req->firstname;
            $user->lastname = $req->lastname;
            $user->type = $req->user_type;
            //$user->is_active = true;
            $user->phone = $req->phone;
            if( $req->password ){
                $user->password = Hash::make($req->password);
            }
            if( $req->email ){
                $user->email = $req->email;
            }
            $user->save();

            $details = UserDetail::where('user_id', $user->id)->first();
            if( empty( $details ) ){
                $details = new UserDetail();
                $details->user_id = $user->id;
            }
            if(request()->hasfile('profile_picture')){
                $user_path              = '/medias/user_docs/'.$user->id.'/';
                $upload_path            = public_path().$user_path;

                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }

                $file                   = request()->file( 'profile_picture' );
                $filename               = $file->getClientOriginalName();
                $file->move( $upload_path, $filename );
                $profilePictureUrl      = $user_path.$filename;
                $attachment             = new Media();
                $attachment->name       = $filename;
                $attachment->type       ='profile-pic';
                $attachment->mimetype   = $file->getClientMimeType();
                $attachment->extension  = $file->getClientOriginalExtension();
                //$attachment->user_id    = Auth::user()->id;
                $attachment->url        = $profilePictureUrl;
                $attachment->path       = $profilePictureUrl;
                $attachment->save();
                $details->attachment_id = $attachment->id;
            }
            $details->role_id           = $req->role_id;
            $details->address1          = $req->address1;
            $details->address2          = $req->address2;
            $details->city              = $req->city;
            $details->state             = $req->state;
            $details->country           = $req->country;
            $details->zip               = $req->zip;
            $details->fax               = $req->fax;
            $details->alternate_email   = $req->alternate_email;
            $details->alternate_phone   = $req->alternate_phone;
            $details->gender            = $req->gender;
            $details->designation       = $req->designation;
            $details->salary            = $req->salary;
            $details->dob               = $req->dob;
            $details->bio               = $req->bio;
            $details->save();
            return \Redirect::route('user-list');
        }else{
            return redirect('401');
        }
    }

    public function saveUserProfile( Request $req ){
        Validator::make($req->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            //'phone' => 'nullable|number',
            //'role_id' => 'required',
        ])->validate();
        $id = $req->user_id;
        $user = User::where('id', $id )->first();
        //dd($user);
        if( !empty( $user ) ){
            $user->firstname = $req->firstname;
            $user->lastname = $req->lastname;
            $user->phone = $req->phone;
            $user->save();

            $details = UserDetail::where('user_id', $user->id)->first();
            if( empty( $details ) ){
                $details = new UserDetail();
                $details->user_id = $user->id;
            }
            if(request()->hasfile('profile_picture')){
                $user_path              = '/medias/user_docs/'.$user->id.'/';
                $upload_path            = public_path().$user_path;
                //dd(public_path());

                if (!file_exists($upload_path)) {
                    mkdir($upload_path, 0755, true);
                }

                $file                   = request()->file( 'profile_picture' );
                $filename               = $file->getClientOriginalName();
                $file->move( $upload_path, $filename );
                $profilePictureUrl      = $user_path.$filename;
                $attachment             = new Media();
                $attachment->name       = $filename;
                $attachment->type       ='profile-pic';
                $attachment->mimetype   = $file->getClientMimeType();
                $attachment->extension  = $file->getClientOriginalExtension();
                //$attachment->user_id    = Auth::user()->id;
                $attachment->url        = $profilePictureUrl;
                $attachment->path       = $profilePictureUrl;
                $attachment->save();
                $details->attachment_id = $attachment->id;
            }
            //$details->role_id           = $req->role_id;
            $details->address1          = $req->address1;
            $details->address2          = $req->address2;
            $details->city              = $req->city;
            $details->state             = $req->state;
            $details->country           = $req->country;
            $details->zip               = $req->zip;
            $details->fax               = $req->fax;
            $details->alternate_email   = $req->alternate_email;
            $details->alternate_phone   = $req->alternate_phone;
            $details->gender            = $req->gender;
            $details->designation       = $req->designation;
            $details->salary            = $req->salary;
            $details->dob               = $req->dob;
            $details->bio               = $req->bio;
            $details->save();
            return redirect()->to(route('user-profile-edit', [$user]));
        }else{
            return redirect('404');
        }
    }

    public function getUserSettings( Request $req, User $id ){
        $user = $id;
        //dd($user->details);
        return view('users.settings')->with(['user'=>$user]);
    }

    // public function saveUserGoogleAuthentication( Request $req, User $id ){
    //     $user = $id;
    //     if( !empty( $user ) ){
    //         $google2fa = app('pragmarx.google2fa');
    //         $user->google2fa_secret = $google2fa->generateSecretKey();
    //         $user->save();
    //         $QR_Image = $google2fa->getQRCodeInline(
    //             config('app.name'),
    //             $user->email,
    //             $user->google2fa_secret
    //         );
    //         $details = [
    //             'qr_image' => $QR_Image,
    //             'secret' => $user->google2fa_secret,
    //         ];
    //         try{
    //             Mail::to($user->email)->send(new googleAuthenticator($details));
    //             //Mail::to('adkarigsourav143@gmail.com')->send(new googleAuthenticator($details));
    //         }catch( Exception $e){
    //             if (isset($ex->errorInfo[2])) {
    //                 dd($ex->errorInfo[2]);
    //             } else {
    //                 dd($ex->getMessage());
    //             }
    //         }
    //     }
    //     return redirect()->to(route('user-list'));
    // }
    public function saveUserGoogleAuthentication(Request $req, User $id)
    {
        $user = $id;

        if (!empty($user)) {

            $google2fa = app('pragmarx.google2fa');

            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();

            // Generate QR Code (base64 image)
            $QR_Image = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $user->google2fa_secret
            );

            $details = [
                'qr_image' => $QR_Image,
                'secret' => $user->google2fa_secret,
                'user_name' => $user->name,
            ];

            try {
                // Using Mailable for professional SMTP delivery via Gmail
                Mail::to($to)->send(new googleAuthenticator($details));
            } catch (\Exception $e) {
                \Log::error('2FA Setup email failed: ' . $e->getMessage());
                return back()->withErrors(['msg' => 'Email delivery failed. Check system logs.']);
            }
        }

        return redirect()->to(route('user-list'));
    }

    public function setUserSettings( Request $req ){
        //dd($req);
        $id = $req->user_id;
        $user = User::where('id', $id )->first();
        //dd($user);
        if( !empty( $user ) ){
            
            $details = UserSetting::where('user_id', $id)->first();
            if( empty( $details ) ){
                $details = new UserSetting();
                $details->user_id = $id;
            } 
            $details->show_profile_pic_to_all           = $req->show_profile_pic_to_all;
            $details->show_profile_pic_to_employee      = $req->show_profile_pic_to_employee;
            $details->enable_message_notification       = $req->enable_message_notification;
            $details->enable_notification_notification  = $req->enable_notification_notification;
            $details->enable_auto_assign_tasks          = $req->enable_auto_assign_tasks;
            $details->save();
            return \Redirect::route('user-settings', [$user]);
        }else{
            //return redirect('404');
        }
    }

    public function changeUserPassword( Request $req, User $id ){
        $user = $id;
        return view('users.password')->with(['user'=>$user]);
    }

    public function updateUserPassword( Request $req ){
        $input = $req->all();
        $userid = Auth::user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return \Redirect::back()->withErrors(['msg' => $validator->errors()->first()]);
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    return \Redirect::back()->withErrors(['msg' => 'Old password mismathced']);
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    return \Redirect::back()->withErrors(['msg' => 'Password and confirm password mismathced']);
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                return \Redirect::back()->withErrors(['msg' => $msg]);
            }
        }
        return redirect('/');
    }

    public function verificationMail( Request $req, User $id ){
        $id->v_code =  rand(10,1000000);
        $id->save();
        $details = [
            'otp' => $id->v_code
        ];
        try {
             // Using AccountVerificationMail class which is already imported
             Mail::to($id->email)->send(new AccountVerificationMail($details));
        }catch( \Exception $e){
            \Log::error('Account verification email failed: ' . $e->getMessage());
            return \Redirect::back()->withErrors(['msg' => 'Email delivery failed.']);
        }
        return \Redirect::route('user-list');
    }
    //public function saveUserProfile( Request $req ){}
}
