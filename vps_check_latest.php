<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$posts = App\Models\Post::orderBy('created_at', 'desc')->take(5)->get(['id', 'title', 'created_at', 'reporter_name']);
foreach($posts as $p) echo "[{$p->created_at}] {$p->title}  (Reporter: {$p->reporter_name})\n";
