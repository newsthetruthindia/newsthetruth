<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// 1. Find Tamal Saha's Articles (including missing ones)
$tamalQuery = DB::table('posts')
    ->where(function($q) {
        $q->where('title', 'LIKE', '%Tamal Saha%')
          ->orWhere('description', 'LIKE', '%Tamal Saha%')
          ->orWhere('reporter_name', 'LIKE', '%Tamal Saha%');
    })
    ->where('user_id', '!=', 7) // Only find those NOT currently assigned to him
    ->select('id', 'title', 'reporter_name', 'user_id')
    ->get();

echo "FINDING MISSING TAMAL SAHA ARTICLES...\n";
foreach ($tamalQuery as $post) {
    echo "   - ID: {$post->id} | UserID: {$post->user_id} | Reporter: {$post->reporter_name} | Title: {$post->title}\n";
}
echo "TOTAL MISSING TAMAL FOUND: " . count($tamalQuery) . "\n\n";

// 2. Double Check Soonakshi Ghosh
$soonakshiQuery = DB::table('posts')
    ->where(function($q) {
        $q->where('title', 'LIKE', '%Soonakshi%')
          ->orWhere('description', 'LIKE', '%Soonakshi%')
          ->orWhere('reporter_name', 'LIKE', '%Soonakshi%');
    })
    ->where('user_id', '!=', 391)
    ->select('id', 'title', 'reporter_name', 'user_id')
    ->get();

echo "FINDING MISSING SOONAKSHI ARTICLES...\n";
foreach ($soonakshiQuery as $post) {
    echo "   - ID: {$post->id} | UserID: {$post->user_id} | Reporter: {$post->reporter_name} | Title: {$post->title}\n";
}
echo "TOTAL MISSING SOONAKSHI FOUND: " . count($soonakshiQuery) . "\n";
?>
