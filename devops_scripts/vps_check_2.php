<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Post::find(4044);
if ($p) {
    echo "Post ID: " . $p->id . "\n";
    echo "Title: " . $p->title . "\n";
    echo "Thumbnail: " . ($p->thumbnails ? "YES (" . $p->thumbnails->url . ")" : "NO") . "\n";
    echo "Categories: " . $p->categories->count() . "\n";
    echo "Tags: " . $p->tags->count() . "\n";
} else {
    echo "Post 4044 not found.";
}
?>
