<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'role_id',
        'address1',
        'address2',
        'state',
        'city',
        'country',
        'zip',
        'fax',
        'alternate_email',
        'alternate_phone',
        'gender',
        'dob',
        'bio',
        'designation',
        'salary',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function role(){
        return $this->belongsTo(UserRole::class);
    }
    /*public function attachment(){
        return $this->hasOne(Attachment::class, 'id', 'attachment_id');
    }*/
}
