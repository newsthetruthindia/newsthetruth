<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "=== REPORTER AUDIT ===\n";
$stats = App\Models\Post::selectRaw('reporter_name, count(*) as count')
    ->groupBy('reporter_name')
    ->get();

foreach ($stats as $s) {
    printf("%-30s | %d\n", $s->reporter_name ?: '[EMPTY]', $s->count);
}
echo "======================\n";
?>
