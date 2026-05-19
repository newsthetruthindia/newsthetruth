<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "=== FINAL REPORTER CLEANUP ===\n";

$mappings = [
    'Rony Santra' => 'Staff Reporter',
    'Ntt Staff' => 'Staff Reporter',
    'NULL' => 'NTT Desk',
];

foreach ($mappings as $old => $new) {
    $count = App\Models\Post::where('reporter_name', $old)->count();
    if ($count > 0) {
        App\Models\Post::where('reporter_name', $old)->update(['reporter_name' => $new]);
        echo "Merged $count articles from '$old' to '$new'\n";
    }
}

// Handle actual NULL values
$nullCount = App\Models\Post::whereNull('reporter_name')->count();
if ($nullCount > 0) {
    App\Models\Post::whereNull('reporter_name')->update(['reporter_name' => 'NTT Desk']);
    echo "Merged $nullCount articles with NULL reporter (unattributed) to 'NTT Desk'\n";
}

echo "=== CLEANUP COMPLETE ===\n";
?>
