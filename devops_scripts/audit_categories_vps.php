<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Category;
use App\Models\Post;

$slugs = ['bengal', 'india', 'politics', 'world', 'business', 'sports', 'entertainment'];
$results = [];

foreach ($slugs as $slug) {
    $cat = Category::where('slug', $slug)->first();
    if ($cat) {
        $latestPost = $cat->posts()->orderBy('created_at', 'desc')->first();
        $results[$slug] = [
            'id' => $cat->id,
            'title' => $cat->title,
            'post_count' => $cat->posts()->count(),
            'latest_post_date' => $latestPost ? $latestPost->created_at->toDateTimeString() : 'N/A',
            'latest_post_title' => $latestPost ? $latestPost->title : 'N/A'
        ];
    } else {
        $results[$slug] = 'Not Found';
    }
}

echo json_encode($results, JSON_PRETTY_PRINT);
