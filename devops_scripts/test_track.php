<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$postId = 1; // Example post
$today = now()->format('Y-m-d');

echo "Pre-count for today ($today): " . \Illuminate\Support\Facades\DB::table('post_views')
    ->where('post_id', $postId)
    ->where('created_at', $today)
    ->value('viewer_count') . "\n";

// Execute tracking logic
\Illuminate\Support\Facades\DB::table('post_views')->updateOrInsert(
    ['post_id' => $postId, 'created_at' => $today],
    ['viewer_count' => \Illuminate\Support\Facades\DB::raw('viewer_count + 1'), 'updated_at' => now()]
);

echo "Post-count for today ($today): " . \Illuminate\Support\Facades\DB::table('post_views')
    ->where('post_id', $postId)
    ->where('created_at', $today)
    ->value('viewer_count') . "\n";
