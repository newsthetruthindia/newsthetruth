<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Starting Final Deep Sweep of Journalistic Attribution...\n";

// 1. Restore Titas Mukherjee (User 9)
$titasCount = DB::table('posts')
    ->where('user_id', 390) // Only check from fallback
    ->where('reporter_name', 'LIKE', '%Titas%')
    ->update(['user_id' => 9]);
echo "   - Restored $titasCount articles to Titas Mukherjee (ID 9).\n";

// 2. Restore Dipaneeta Das (User 385)
$dipCount = DB::table('posts')
    ->where('user_id', 390)
    ->where('reporter_name', 'LIKE', '%Dipaneeta%')
    ->update(['user_id' => 385]);
echo "   - Restored $dipCount articles to Dipaneeta Das (ID 385).\n";

echo "FINAL DEEP SWEEP COMPLETE.\n";
?>
