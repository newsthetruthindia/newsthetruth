<?php 
require "/var/www/ntt/vendor/autoload.php";
$app = require_once "/var/www/ntt/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$post = \App\Models\Post::where("title", "like", "%Santu Pan%")->first();
if ($post) {
    echo "ID: " . $post->id . "\n";
    echo "Title: " . $post->title . "\n";
    echo "Thumbnail ID: " . $post->thumbnail . "\n";
    if ($post->thumbnails) {
        echo "Thumbnail URL: " . $post->thumbnails->url . "\n";
        echo "Thumbnail Absolute: " . $post->thumbnails->getUrlAttribute("") . "\n";
    } else {
        echo "Thumbnail Relation MISSING\n";
    }
} else {
    echo "Post NOT FOUND\n";
}
