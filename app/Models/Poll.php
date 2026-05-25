<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'post_id', 'is_active'];

    protected static function booted()
    {
        static::saving(function ($poll) {
            if ($poll->is_active) {
                // Deactivate all other polls
                Poll::where('id', '!=', $poll->id)->update(['is_active' => false]);
            }
        });
    }

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }
}
