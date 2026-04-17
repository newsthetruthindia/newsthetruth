<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser, HasAvatar, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use SoftDeletes;

    protected $appends = ['is_reporter'];

    public function getFilamentAvatarUrl(): ?string
    {
        $media = $this->details?->media;
        if (!$media || !$media->url) {
            return null;
        }

        $path = ltrim($media->url, '/');
        
        // Use the standardized storage proxy for consistency
        return asset('storage/' . $path);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['Admin', 'Editor']);
    }

    public function getNameAttribute(): string
    {
        return trim(($this->firstname ?? '') . ' ' . ($this->lastname ?? ''));
    }

    public function getIsReporterAttribute(): bool
    {
        return $this->hasRole('Reporter') || $this->hasRole('Reporter', 'web') || $this->roles()->where('name', 'Reporter')->exists();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function details(){
        return $this->hasOne( UserDetail::class );
    }

    public function settings(){
        return $this->hasOne( UserSetting::class );
    }

    public function teamCreated(){
        return $this->hasMany( Team::class );
    }

    public function legacyRole(){
        return $this->hasOne( UserRole::class, 'id', 'role_id' );
    }

    public function thumbnails(): HasOneThrough{
        return $this->hasOneThrough( Media::class, UserDetail::class, 'user_id','id', 'id', 'attachment_id' );
    }

    public function posts(){
        return $this->hasMany( Post::class );
    }
}
