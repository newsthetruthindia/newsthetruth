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
        'user_id',
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
        'published',
        'post_publish_time',
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
