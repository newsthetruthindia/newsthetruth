<?php
/**
 * NTT Article Attribution Fix
 * Reassigns articles owned by Admin (390) to their respective reporters if reporter_name is set.
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;
use App\Models\User;

echo "--- Starting Attribution Fix ---\n";

// 1. Identify Tamal Saha (ID 7)
$tamal = User::where('firstname', 'Tamal')->where('lastname', 'Saha')->first();
if ($tamal) {
    $count = Post::where('user_id', 390)
        ->where('reporter_name', 'LIKE', '%Tamal Saha%')
        ->update(['user_id' => $tamal->id]);
    echo "Reassigned $count posts to Tamal Saha (ID: {$tamal->id}).\n";
} else {
    echo "Tamal Saha user not found.\n";
}

// 2. Identify Dipaneeta Das (ID 385)
$dipaneeta = User::where('firstname', 'Dipaneeta')->where('lastname', 'Das')->first();
if ($dipaneeta) {
    $count = Post::where('user_id', 390)
        ->where('reporter_name', 'LIKE', '%Dipaneeta Das%')
        ->update(['user_id' => $dipaneeta->id]);
    echo "Reassigned $count posts to Dipaneeta Das (ID: {$dipaneeta->id}).\n";
}

// 3. Identify Soonakshi Ghosh (ID 391)
$soonakshi = User::where('firstname', 'Soonakshi')->where('lastname', 'Ghosh')->first();
if ($soonakshi) {
    $count = Post::where('user_id', 390)
        ->where('reporter_name', 'LIKE', '%Soonakshi Ghosh%')
        ->update(['user_id' => $soonakshi->id]);
    echo "Reassigned $count posts to Soonakshi Ghosh (ID: {$soonakshi->id}).\n";
}

// 4. Identify Ankit Salvi (ID?)
$ankit = User::where('firstname', 'Ankit')->where('lastname', 'Salvi')->first();
if ($ankit) {
    $count = Post::where('user_id', 390)
        ->where('reporter_name', 'LIKE', '%Ankit Salvi%')
        ->update(['user_id' => $ankit->id]);
    echo "Reassigned $count posts to Ankit Salvi (ID: {$ankit->id}).\n";
}

echo "--- Attribution Fix Complete ---\n";
