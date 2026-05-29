<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sent_date',
        'recipients_count',
        'posts_snapshot',
    ];

    protected $casts = [
        'sent_date' => 'date',
        'posts_snapshot' => 'array',
    ];
}
