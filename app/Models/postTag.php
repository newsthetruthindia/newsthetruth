<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class postTag extends Model
{
    use HasFactory;
    protected $fillable =[
        'post_id',
        'tag_id'
    ];

    public function tag_data(){
        return $this->hasOne( Tag::class, 'id', 'tag_id' );
    }
}
