<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "=== CONSOLIDATING REPORTER NAMES ===\n";

// 1. Merge Rony Santra into Staff Reporter
$ronyCount = App\Models\Post::where('reporter_name', 'Rony Santra')->count();
if ($ronyCount > 0) {
    App\Models\Post::where('reporter_name', 'Rony Santra')
        ->update(['reporter_name' => 'Staff Reporter']);
    echo "Successfully merged $ronyCount articles from 'Rony Santra' to 'Staff Reporter'\n";
} else {
    echo "No articles found for 'Rony Santra'\n";
}

// 2. Merge Titas into Titas Mukherjee
$titasCount = App\Models\Post::where('reporter_name', 'Titas')->count();
if ($titasCount > 0) {
    App\Models\Post::where('reporter_name', 'Titas')
        ->update(['reporter_name' => 'Titas Mukherjee']);
    echo "Successfully merged $titasCount articles from 'Titas' to 'Titas Mukherjee'\n";
} else {
    echo "No articles found for 'Titas'\n";
}

echo "=== CONSOLIDATION COMPLETE ===\n";
?>
