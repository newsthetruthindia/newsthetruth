<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    protected $fillable = ['question', 'is_active', 'expires_at'];

    protected $casts = [
        'is_active'  => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class);
    }

    /** Scope to get only currently active, non-expired polls */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }

    /** Total number of votes across all options */
    public function totalVotes(): int
    {
        return $this->options->sum('votes');
    }
}
