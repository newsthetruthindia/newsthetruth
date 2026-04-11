<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMonitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'youtube_urls',
        'rss_feeds',
    ];

    protected $casts = [
        'youtube_urls' => 'array',
        'rss_feeds' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
