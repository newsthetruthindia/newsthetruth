<?php

// NTT Desk Sync Script
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;

try {
    $targetUserId = 397; // The new NTT Desk User ID
    $names = ['Ntt Desk', 'NTT Desk', 'NTT Desk (Official)'];
    
    $count = Post::whereIn('reporter_name', $names)
        ->update(['user_id' => $targetUserId]);

    echo "Successfully synced $count articles to NTT Desk (User ID: $targetUserId).\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
