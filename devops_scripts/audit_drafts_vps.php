<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Post;

$drafts = Post::where('status', '!=', 'published')->get(['id', 'title', 'status', 'created_at'])->toArray();

echo json_encode($drafts, JSON_PRETTY_PRINT);
