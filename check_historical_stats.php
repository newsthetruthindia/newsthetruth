<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Post;

$minDate = Post::min('created_at');
$totalPosts = Post::count();

echo "Oldest Post Date (VPS): " . ($minDate ?? 'None') . "\n";
echo "Total Posts (VPS): " . $totalPosts . "\n";
?>
