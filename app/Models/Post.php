<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

use App\Models\Option;
use App\Models\User;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'description',
        'excerpt',
        'thumbnail',
        'status',
        'shares',
        'visibility',
        'image_credit',
        'location',
        'reporter_name',
        'user_id',
        'published',
        'post_publish_time',
        'meta_title',
        'meta_description',
        'audio_clip_url',
        'video_url',
        'x_embed_url',
        'gallery_position',
    ];
    public function user(){
        return $this->belongsTo( User::class );
    }

    public function metas(){
        return $this->hasMany( Postmeta::class );
    }

    public function thumbnails(){
        return $this->hasOne( Media::class, 'id', 'thumbnail' );
    }
    public function categories(){
        return $this->hasMany( postCategory::class, 'post_id', 'id' );
    }
    public function filamentCategories(){
        return $this->belongsToMany(Category::class, 'post_categories', 'post_id', 'category_id');
    }
    public function thumbnailMedia(){
        return $this->belongsTo(Media::class, 'thumbnail', 'id');
    }
    public function tags(){
        return $this->hasMany( postTag::class, 'post_id', 'id' );
    }
    public function filamentTags(){
        return $this->belongsToMany(Tag::class, 'post_tags', 'post_id', 'tag_id');
    }
    public function gallery(){
        return $this->hasMany( PostGallery::class, 'post_id', 'id' );
    }
    public function galleryMedias(){
        return $this->belongsToMany(Media::class, 'post_galleries', 'post_id', 'media_id')->withTimestamps();
    }

    protected static function booted()
    {
        static::saving(function ($post) {
            // Automatically resolve user_id from reporter_name if mismatch found
            if (!empty($post->reporter_name)) {
                $name = trim($post->reporter_name);
                try {
                    // Look for an official reporter with this name
                    // Using COALESCE to handle null firstname/lastname and preventing crash if role missing
                    $matchedUser = User::role('Reporter')
                        ->whereRaw("TRIM(CONCAT(COALESCE(firstname, ''), ' ', COALESCE(lastname, ''))) = ?", [$name])
                        ->first();
                    
                    if ($matchedUser) {
                        $post->user_id = $matchedUser->id;
                    }
                } catch (\Exception $e) {
                    // Fail gracefully if role doesn't exist or SQL error occurs
                    \Illuminate\Support\Facades\Log::warning("Reporter resolution failed: " . $e->getMessage());
                }
            }
        });

        static::saved(function ($post) {
            // Send notification if status was just changed to 'published' or if it was newly created as published
            if (($post->wasRecentlyCreated || $post->wasChanged('status')) && $post->status === 'published') {
                try {
                    $imageUrl = $post->thumbnailMedia ? asset('storage/' . ltrim($post->thumbnailMedia->url, '/')) : null;
                    
                    // 1. BROADCAST NOTIFICATIONS
                    $broadcastOption = Option::where('key', 'automatic_notifications')->first();
                    if ($broadcastOption && $broadcastOption->value === '1') {
                        $subscribers = User::where('type', 'user')->get();
                        if ($subscribers->isNotEmpty()) {
                            \Illuminate\Support\Facades\Notification::send(
                                $subscribers, 
                                new \App\Notifications\BroadcastNotification(
                                    $post->title,
                                    env('FRONTEND_URL', 'https://newsthetruth.com') . '/news/' . $post->slug,
                                    $post->excerpt,
                                    $imageUrl
                                )
                            );
                        }
                    }

                    // 2. SOCIAL AUTO-PUBLISHING
                    $socialOption = Option::where('key', 'automatic_social_publish')->first();
                    if ($socialOption && $socialOption->value === '1' && !$post->is_social_published) {
                        $service = new \App\Services\SocialPublishingService();
                        $link = rtrim(env('FRONTEND_URL', 'https://newsthetruth.com'), '/') . '/news/' . $post->slug;
                        
                        // Publish to Facebook
                        $service->publishToFacebook($post->title . "\n\n" . $post->excerpt, $link);
                        
                        // Publish to Instagram (requires image)
                        if ($imageUrl) {
                            $service->publishToInstagram($post->title . "\n\n" . $post->excerpt . "\n\n👉 Read more at link in bio!", $imageUrl);
                        }

                        // Mark as published to avoid double posting on subsequent saves
                        // Use silent update to avoid triggering 'saved' hook recursively
                        $post->timestamps = false;
                        $post->updateQuietly(['is_social_published' => true]);
                        $post->timestamps = true;
                    }

                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Post saved hooks failed: " . $e->getMessage());
                }
            }
        });
    }
}
