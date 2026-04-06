<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== NTT REPORTER AUDIT ===\n";

$stats = Post::select('reporter_name', DB::raw('count(*) as count'), 'user_id')
    ->groupBy('reporter_name', 'user_id')
    ->get();

$allReporters = [];
foreach ($stats as $s) {
    if (!$s->reporter_name) continue;
    $name = trim($s->reporter_name);
    if (!isset($allReporters[$name])) {
        $allReporters[$name] = ['total_posts' => 0, 'mappings' => []];
    }
    $allReporters[$name]['total_posts'] += $s->count;
    $allReporters[$name]['mappings'][] = [
        'user_id' => $s->user_id,
        'count' => $s->count
    ];
}

foreach ($allReporters as $name => $data) {
    $user = User::where(function($q) use ($name) {
        $parts = explode(' ', $name);
        $q->where(DB::raw("CONCAT(firstname, ' ', lastname)"), 'LIKE', "%$name%");
    })->first();

    $userId = $user ? $user->id : '[MISSING]';
    printf("Reporter: %-25s | Total Posts: %-4d | UserID: %s\n", $name, $data['total_posts'], $userId);
}

echo "==========================\n";
?>
