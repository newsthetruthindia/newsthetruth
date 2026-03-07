<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class postCategory extends Model
{
    use HasFactory;
    protected $fillable =[
        'post_id',
        'category_id'
    ];

    public function cat_data(){
        return $this->hasOne( Category::class, 'id', 'category_id' );
    }
    public function post(){
        return $this->hasOne( Post::class, 'id', 'post_id' );
    }
}
