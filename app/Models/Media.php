<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Media extends Model
{
    use HasFactory;
    protected $table = 'medias';
    protected $fillable = [
        'type',
        'path',
        'mimetype',
        'extension',
        'url',
        'name',
        'alt',
        'description',
        'height',
        'width',
    ];
}
