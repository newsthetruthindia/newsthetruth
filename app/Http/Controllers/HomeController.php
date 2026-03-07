<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Mail\AccountVerificationMail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        
        $data = [];
        $month_ini = new \DateTime("first day of last month");
        $month_end = new \DateTime("last day of last month");
        if( Auth::user()->type =='admin'){
            $data['posts']['total'] = count(Post::get());
            $data['posts']['month_total'] = count(Post::whereDate('created_at','>=', date('y-m-1') )->get());
            $data['posts']['prev_month_total'] = count(Post::whereBetween('created_at', [$month_ini, $month_end] )->get());
            $data['categories']['total'] = count(Category::get());
            $data['categories']['month_total'] = count(Category::whereDate('created_at','>=', date('y-m-1') )->get());
            $data['categories']['prev_month_total'] = count(Category::whereBetween('created_at', [$month_ini, $month_end] )->get());
            $data['user']['total'] = count(User::get());
            $data['user']['month_total'] = count(User::whereDate('created_at','>=', date('y-m-1') )->get());
            $data['user']['prev_month_total'] = count(User::whereBetween('created_at', [$month_ini, $month_end] )->get());
            $data['published_posts']['total'] = count(Post::where('status', 'published')->get());
            $data['published_posts']['month_total'] = count(Post::whereDate('created_at','>=', date('y-m-1') )->where('status', 'published')->get());
            $data['published_posts']['prev_month_total'] = count(Post::whereBetween('created_at', [$month_ini, $month_end] )->where('status', 'published')->get());
        }
        //dd($data);
        return view('home')->with(['data'=>$data]);
    }

    public function unauthorized( Request $request){
        return view('unauthorized');
    }
    
    public function unauthenticated( Request $request)
    {
        return view('unauthenticated');
    }
    
    public function mailSend( Request $request) {
       return view('public-pages.mailsend');
    }
    
    public function mailVerify( Request $request) {
        $to = $request->to;
        $description = $request->description;
        $details = [
            'otp' => 'V5f$ddW9',
            'description' => $description,
        ];
        try{
             Mail::to($to)->send(new AccountVerificationMail($details));
        }catch( Exception $e){
            if (isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            } else {
                $msg = $ex->getMessage();
            }
            return \Redirect::back()->withErrors(['msg' => $msg]);
        }
        return \Redirect::route('mail-verification');
    }
    public function cron( Request $req ){
        $user = User::first();
        $user->cron = 1;
        $user->save();
    }
}
