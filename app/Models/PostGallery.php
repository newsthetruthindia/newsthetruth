<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostGallery extends Model
{
    use HasFactory;
    protected $fillable =[
        'post_id',
        'media_id'
    ];

    public function cat_data(){
        return $this->hasOne( Media::class, 'id', 'media_id' );
    }
}
