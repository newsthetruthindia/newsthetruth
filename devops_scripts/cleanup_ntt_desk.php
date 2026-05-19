<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "=== FINAL NTT DESK CONSOLIDATION ===\n";

$validReporters = [
    'Titas Mukherjee',
    'Ankit Salvi',
    'Tamal Saha',
    'Dipaneeta Das',
    'Staff Reporter',
    'Soonakshi Ghosh',
    'Suprav Banerjee',
    'Aniket Datta',
    'Sankha Subhra Das',
    'NTT Desk' // Exclude the target name too
];

$query = App\Models\Post::whereNotIn('reporter_name', $validReporters)
    ->orWhereNull('reporter_name');

$count = $query->count();

if ($count > 0) {
    echo "Identified $count articles with anomalous or NULL reporter names.\n";
    $query->update(['reporter_name' => 'NTT Desk']);
    echo "SUCCESS: All $count articles have been merged into 'NTT Desk'.\n";
} else {
    echo "No anomalous articles found.\n";
}

echo "=== CONSOLIDATION COMPLETE ===\n";
?>
