<?php
// fix_user_ids.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

$reporterMap = [
    'Titas Mukherjee' => 9,
    'Dipaneeta Das'   => 385,
    'NTT Desk'        => 390,
];

echo "Updating User IDs to match Reporter Names for recent articles...\n";

foreach ($reporterMap as $name => $userId) {
    $affected = DB::table('posts')
        ->where('reporter_name', $name)
        ->where('id', '>=', 4160)
        ->update(['user_id' => $userId]);
    
    echo "Updated $affected posts for $name to User ID $userId.\n";
}

echo "USER ID CORRECTION COMPLETE\n";
?>
