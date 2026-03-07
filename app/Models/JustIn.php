<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JustIn extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'just_in';
    public function user(){
        return $this->belongsTo( User::class );
    }
    
    public function parent(){
        return $this->hasOne( JustIn::class, 'id', 'just_in_id' );
    }

    public function thumbnails(){
        return $this->hasOne( Media::class, 'id', 'thumbnail' );
    }
}
