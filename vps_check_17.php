<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$p = App\Models\Post::find(17);
echo "Post 17: " . ($p ? $p->title : "Not Found") . "\n";
$first = App\Models\Post::orderBy('id', 'asc')->first();
echo "First post ID: " . $first->id . " Title: " . $first->title . " Date: " . $first->created_at . "\n";
