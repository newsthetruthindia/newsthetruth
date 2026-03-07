<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Notification;

class NotificationController extends Controller{
    public $to              = [];

    public $type            = 'general';

    public $prop            = [];

    public $role_ids         = [];

    public $description     = 'A new Noticication';

    public function __construct($prop=[], $type='general', $to=[], $send=false, $roles=[] ){
        $this->to           = $to;
        $this->type         = $type;
        $this->prop         = $prop;
        $this->role_ids     = $roles;
        if( $send ){
            $this->setType($type);
            $this->send();
        }
    }
    public function getAll( Request $request ){
        $notification = Notification::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(10);
        return view('notifications')->with(['notifications'=>$notification]);
    }
    public function getTopFive( Request $request ){
        $notification = Notification::where('user_id', Auth::user()->id)->where('is_read', '0')->orderBy('id', 'DESC')->paginate(3);
        //$notification = Notification::where('is_read', '0')->paginate(5);
        return json_encode(['item'=>$notification]);
    }

    public function update( Request $request, Notification $notification ){
        if( !empty( $notification ) ){
            $notification->is_read = 1;
            $notification->read_at = date('Y-m-d G:i:s');
            $notification->save();
        }
        return redirect()->to(route('notifications'));
    }

    public function setType( $type='general' ){
        switch( $type ){
            case 'users':
                $this->setAllUsers();
            break;
            case 'admin':
                $this->setAdmin();
            break;
            case 'user':
                $this->setAnUser();
            break;
            case 'role':
                $this->setRoleSpecific();
            break;
            default:
                $this->setActiveUser();
            break;
        }
    }

    private function setAllUsers(){
        $users = User::whereNot('type', 'user')->get();
        if( !empty( $users ) ){
            foreach( $users as $user){
                $this->to[]=$user;
            }
        }
    }

    private function setAdmin(){
        $users = User::where('type', 'admin')->get();
        if( !empty( $users ) ){
            foreach( $users as $user){
                $this->to[]=$user;
            }
        }
    }

    private function setRoleSpecific(){
        $role_ids = $this->role_ids;
        $users = User::whereHas( 'user_details', function( $query ) use($role_ids){
            $query->whereIn( 'role_id', $role_ids );
        })->get();
        if( !empty( $users ) ){
            foreach( $users as $user){
                $this->to[]=$user;
            }
        }
    }

    private function setAnUser(){
        $users = User::whereIn( 'id', $this->to )->first();
        if( !empty( $users ) ){
            foreach( $users as $user){
                $this->to[]=$user;
            }
        }
    }

    private function setActiveUser(){
        $this->to[]= Auth::user();
    }

    public function get( Request $request, Notification $n ){}

    public function init( $prop = [] ){
        if( !empty( $prop['to'] ) ){
            if( is_array( $prop['to'] ) ){
                $this->to = $prop['to'];
            }else{
                $this->to = [ $prop['to'] ];
            }
        }
        if( !empty( $prop['type'] ) ){
            $this->setType( $prop['type'] );
        }
    }

    public function send(){
        $userids = [];
        if( !empty( $this->to ) ){
            if( is_array( $this->to ) ){
                //dd($this->to);
                foreach ( $this->to as $touser ) {
                    if( $touser instanceof User ){
                        $notification = new Notification();
                        $notification->description = $this->description;
                        $notification->user_id = $touser->id;
                        $notification->save();
                        //array_push($userids, $touser->id);
                    }
                }
            }else{
                if( $this->to instanceof User ){
                    $notification = new Notification();
                    $notification->description = $this->description;
                    $notification->user_id = $this->to->id;
                    $notification->save();
                    //array_push($userids, $this->to->id);
                }
            }
        }
        /*if( !empty( $userids )){
            $users = User::whereIn('id', $userids)->whereNotNull('device_key')->pluck('device_key')->all();
            if( !empty( $users )){
                $notification =[
                    'title'=>'New Notification',
                    'body'=>$this->description,
                ];
                //$this->pushNotification($users, $notification );
            }
        }*/
    }

    public function description( $desc='' ){
        $this->description=$desc;
    }

    public function type( $type = 'general' ){
        $this->setType( $type );
    }

    public function pushNotification($tockens=[], $notification=['title'=>'', 'body'=>'']){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = $tockens;//User::whereNotNull('device_key')->pluck('device_key')->all();
        //dd($FcmToken);
        $serverKey = 'AAAAPnMorxA:APA91bHybMbes-5rcQr8ZNmzEFhm31hRrNRldzBLHgOFv8c3g6y2FqjGyBEA7aQks8T-qS0UBOdqV9j-tat23WRwaHxGdx_5OQU7-YtclD-3KsjmmkTn_yb0BCiHTs5X5f2N2E6fKrAq';
  
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => $notification
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // FCM response
        //dd($result);        
    }
}
