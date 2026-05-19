<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Post;
use App\Models\Media;

$posts = Post::orderBy('created_at', 'desc')->take(10)->get();
$results = [];

foreach ($posts as $p) {
    if ($p->thumbnail) {
        $media = Media::find($p->thumbnail);
        if ($media) {
            $path = $media->path;
            $fullPath = storage_path('app/public/' . $path);
            $results[] = [
                'post_id' => $p->id,
                'thumb_id' => $p->thumbnail,
                'media_path' => $path,
                'exists' => file_exists($fullPath),
                'full_path' => $fullPath
            ];
        } else {
            $results[] = [
                'post_id' => $p->id,
                'thumb_id' => $p->thumbnail,
                'error' => 'Media metadata not found in database.'
            ];
        }
    } else {
        $results[] = [
            'post_id' => $p->id,
            'error' => 'No thumbnail ID set for this post.'
        ];
    }
}

echo json_encode($results, JSON_PRETTY_PRINT);
