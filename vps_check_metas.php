<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$post = App\Models\Post::orderBy('created_at', 'desc')->first();
$metas = $post->metas;
echo "Post ID: " . $post->id . "\n";
echo "Post Title: " . $post->title . "\n";
echo "Post Meta Count: " . count($metas) . "\n";
foreach($metas as $m) echo $m->key . " = " . $m->description . "\n";
