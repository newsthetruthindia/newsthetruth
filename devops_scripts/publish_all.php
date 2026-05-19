<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "=== BATCH PUBLISHING ARTICLES ===\n";
$affected = App\Models\Post::where('status', '!=', 'published')
    ->orWhereNull('status')
    ->update(['status' => 'published']);

echo "Successfully published $affected articles.\n";
echo "=================================\n";
?>
