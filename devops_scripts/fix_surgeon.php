<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$updates = [
    4193 => ['reporter' => 'Titas Mukherjee', 'id' => 9],
    4192 => ['reporter' => 'Titas Mukherjee', 'id' => 9],
    4191 => ['reporter' => 'Soonakshi Ghosh', 'id' => 391],
    4190 => ['reporter' => 'Tamal Saha', 'id' => 7],
    4189 => ['reporter' => 'Tamal Saha', 'id' => 7]
];

foreach ($updates as $postId => $data) {
    if ($post = App\Models\Post::find($postId)) {
        $post->user_id = $data['id'];
        $post->reporter_name = $data['reporter'];
        $post->timestamps = false;
        $post->save();
        echo "Updated post $postId to {$data['reporter']}\n";
    }
}

$uncategorized = App\Models\Post::orderBy('created_at', 'desc')->take(20)->get();
foreach ($uncategorized as $p) {
    if ($p->user_id === 1 || empty($p->reporter_name) || strtolower($p->reporter_name) === 'ntt desk') {
        $p->user_id = 9; 
        $p->reporter_name = 'Titas Mukherjee';
        $p->timestamps = false;
        $p->save();
        echo "Fallback updated post {$p->id} to Titas Mukherjee\n";
    }
}
