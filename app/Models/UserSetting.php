<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'show_profile_pic_to_all',
        'show_profile_pic_to_employee',
        'enable_message_notification',
        'enable_notification_notification',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
