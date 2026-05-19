<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// 1. Find Soonakshi Articles
$soonakshiQuery = DB::table('posts')
    ->where('title', 'LIKE', '%Soonakshi%')
    ->orWhere('description', 'LIKE', '%Soonakshi%')
    ->orWhere('reporter_name', 'LIKE', '%Soonakshi%')
    ->select('id', 'title', 'reporter_name')
    ->get();

echo "FINDING SOONAKSHI ARTICLES...\n";
foreach ($soonakshiQuery as $post) {
    echo "   - ID: {$post->id} | Title: {$post->title} | Reporter: {$post->reporter_name}\n";
}
echo "TOTAL SOONAKSHI FOUND: " . count($soonakshiQuery) . "\n\n";

// 2. Identify Publisher Articles (Rony & Arko)
$publisherArticles = DB::table('posts')
    ->whereIn('user_id', [2, 193])
    ->orWhereIn('reporter_name', ['Rony Santra', 'Arko Saha'])
    ->select('id', 'title', 'reporter_name', 'user_id')
    ->get();

echo "AUDITING PUBLISHER ARTICLES (Rony/Arko)...\n";
foreach ($publisherArticles as $post) {
    echo "   - ID: {$post->id} | UserID: {$post->user_id} | Reporter: {$post->reporter_name} | Title: {$post->title}\n";
}
echo "TOTAL PUBLISHER ARTICLES FOUND: " . count($publisherArticles) . "\n";
?>
