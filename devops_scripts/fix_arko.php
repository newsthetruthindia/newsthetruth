<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "=== CORRECTING ARKO SAHA ATTRIBUTION ===\n";
$count = App\Models\Post::where('reporter_name', 'Arko Saha')->count();
echo "Found $count articles with reporter_name = 'Arko Saha'\n";

if ($count > 0) {
    App\Models\Post::where('reporter_name', 'Arko Saha')
        ->update(['reporter_name' => 'NTT Desk']);
    echo "Updated $count articles to 'NTT Desk'\n";
} else {
    echo "No articles to update.\n";
}
echo "========================================\n";
?>
