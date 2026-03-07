<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Page extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    public function user(){
        return $this->belongsTo( User::class );
    }

    public function metas(){
        return $this->hasMany( pageMeta::class );
    }

    public function thumbnails(){
        return $this->hasOne( Media::class, 'id', 'thumbnail' );
    }
    public function categories(){
        return $this->hasMany( postCategory::class, 'post_id', 'id' );
    }
    public function tags(){
        return $this->hasMany( postTag::class, 'post_id', 'id' );
    }
    public function gallery(){
        return $this->hasMany( PostGallery::class, 'post_id', 'id' );
    }
}
