<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'user_id',
        'subtitle',
        'description',
        'thumbnail',
    ];

    protected static function booted()
    {
        static::creating(function ($tag) {
            if (empty($tag->user_id)) {
                $tag->user_id = auth()->id() ?? 0;
            }
        });
    }
}
