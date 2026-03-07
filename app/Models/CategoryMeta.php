<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryMeta extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id',
        'key',
        'category_id',
        'description',
    ];
    //protected $table = 'post_metas';
}
