<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Category extends Model
{
    use HasFactory;

    public function cats(){
        return $this->hasMany( postCategory::class, 'category_id', 'id' );
    }
    
    public function metas(){
        return $this->hasMany( CategoryMeta::class );
    }

    public function thumbnails(){
        return $this->hasOne( Media::class, 'id', 'thumbnail' );
    }
}
