<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BroadcastLog extends Model
{
    protected $fillable = [
        'title',
        'url',
        'recipient_count',
        'status',
    ];
}
