<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$posts = App\Models\Post::orderBy('created_at', 'desc')->take(5)->get();
foreach ($posts as $p) {
    echo "ID " . $p->id . ": " . strip_tags(substr($p->description, 0, 500)) . "\n\n";
}
