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

    protected static function booted()
    {
        static::saving(function ($post) {
            // Automatically resolve user_id from reporter_name if mismatch found
            if (!empty($post->reporter_name)) {
                $name = trim($post->reporter_name);
                // Look for an official reporter with this name
                $matchedUser = User::role('Reporter')
                    ->whereRaw("TRIM(CONCAT(firstname, ' ', lastname)) = ?", [$name])
                    ->first();
                
                if ($matchedUser) {
                    $post->user_id = $matchedUser->id;
                }
            }
        });

        static::saved(function ($post) {
            // Only send if status was just changed to 'published'
            if ($post->wasChanged('status') && $post->status === 'published') {
                $autoNotify = Option::where('key', 'automatic_notifications')->first()?->value === '1';
                
                if ($autoNotify) {
                    $subscribers = User::where('type', 'user')->get();
                    $imageUrl = $post->thumbnailMedia ? asset('storage/' . $post->thumbnailMedia->url) : null;

                    \Illuminate\Support\Facades\Notification::send(
                        $subscribers, 
                        new \App\Notifications\BroadcastNotification(
                            $post->title,
                            env('APP_URL') . '/posts/' . $post->slug,
                            $post->excerpt,
                            $imageUrl
                        )
                    );
                }
            }
        });
    }
}
