<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$posts = App\Models\Post::orderBy('created_at', 'desc')->take(10)->get(['id', 'title', 'reporter_name']);
foreach ($posts as $p) {
    echo "ID " . $p->id . ": " . $p->title . " => " . $p->reporter_name . "\n";
}
