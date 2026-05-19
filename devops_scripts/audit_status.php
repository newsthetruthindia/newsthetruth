<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "=== POST STATUS AUDIT ===\n";
$stats = App\Models\Post::selectRaw('status, count(*) as count')
    ->groupBy('status')
    ->get();

foreach ($stats as $s) {
    printf("%-20s | %d\n", $s->status ?: '[EMPTY]', $s->count);
}
echo "=========================\n";
?>
