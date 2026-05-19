<?php
// PHP Script to fix missing thumbnail and hero status for post 4194
$media = App\Models\Media::create([
    "path" => "uploads/media/01KNM82FS34XJWZT0N71Z9E3RB.jpg",
    "type" => "image",
    "mimetype" => "image/jpeg",
    "extension" => "jpg",
    "url" => "uploads/media/01KNM82FS34XJWZT0N71Z9E3RB.jpg",
    "name" => "01KNM82FS34XJWZT0N71Z9E3RB.jpg"
]);

$post = App\Models\Post::find(4194);
if ($post) {
    $post->thumbnail = $media->id;
    $post->top_post = 1;
    $post->status = "published";
    $post->save();
    echo "SUCCESS: Media ID " . $media->id . " created and linked to Post 4194 (Hero Status Active)\n";
} else {
    echo "ERROR: Post 4194 not found\n";
}
