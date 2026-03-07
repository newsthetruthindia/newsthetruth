<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function details(){
        return $this->hasOne( UserDetail::class );
    }

    public function settings(){
        return $this->hasOne( UserSetting::class );
    }

    public function teamCreated(){
        return $this->hasMany( Team::class );
    }

    public function role(){
        return $this->hasOne( UserRole::class, 'id', 'role_id' );
    }

    public function thumbnails(): HasOneThrough{
        return $this->hasOneThrough( Media::class, UserDetail::class, 'user_id','id', 'id', 'attachment_id' );
    }
}
