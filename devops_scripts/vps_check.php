<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Post::latest()->first();
if ($p) {
    echo "Latest Post ID: " . $p->id . "\n";
    echo "Title: " . $p->title . "\n";
    echo "Thumbnail: " . ($p->thumbnails ? "YES (" . $p->thumbnails->url . ")" : "NO") . "\n";
    echo "Categories: " . $p->categories->count() . "\n";
    $attrs = $p->getAttributes();
    echo "Image Credit Field: " . (array_key_exists("image_credit", $attrs) ? "FOUND" : "MISSING") . "\n";
    echo "Location Field: " . (array_key_exists("location", $attrs) ? "FOUND" : "MISSING") . "\n";
} else {
    echo "No posts found.";
}
?>
