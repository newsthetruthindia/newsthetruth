<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category_id',
        'thumbnail',
    ];

    public function posts(){
        return $this->belongsToMany(Post::class, 'post_categories', 'category_id', 'post_id');
    }

    public function parent(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function children(){
        return $this->hasMany(Category::class, 'category_id');
    }

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
