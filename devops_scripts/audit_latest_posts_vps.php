<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Post;

$latestPosts = Post::orderBy('created_at', 'desc')->take(10)->get(['id', 'title', 'created_at', 'category_id'])->toArray();

echo json_encode($latestPosts, JSON_PRETTY_PRINT);
