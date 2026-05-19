<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== USER COUNTS ===\n";
$users = DB::table('users')->select('id', 'firstname', 'lastname', 'email')->get();
foreach ($users as $user) {
    $postCount = DB::table('posts')->where('user_id', $user->id)->count();
    $reporterNameCount = DB::table('posts')->where('reporter_name', $user->firstname . ' ' . $user->lastname)->count();
    echo "ID: {$user->id} | Name: {$user->firstname} {$user->lastname} | Email: {$user->email} | UserID Posts: {$postCount} | ReporterName Posts: {$reporterNameCount}\n";
}

echo "\n=== DISTINCT REPORTER NAMES IN POSTS ===\n";
$reporters = DB::table('posts')->select('reporter_name')->distinct()->get();
foreach ($reporters as $r) {
    $count = DB::table('posts')->where('reporter_name', $r->reporter_name)->count();
    echo "Reporter Name: " . ($r->reporter_name ?: '[EMPTY]') . " | Count: $count\n";
}
?>
