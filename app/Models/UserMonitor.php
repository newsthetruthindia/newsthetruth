<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMonitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'secret_key',
        'youtube_urls',
        'rss_feeds',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->secret_key)) {
                $model->secret_key = \Illuminate\Support\Str::random(12);
            }
        });
    }

    protected $casts = [
        'youtube_urls' => 'array',
        'rss_feeds' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
