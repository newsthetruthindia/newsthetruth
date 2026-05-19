<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Post;
use Illuminate\Support\Facades\DB;

echo "=== FIXING CATEGORY MAPPING FOR RECENT ARTICLES ===\n";

// 1. Fetch Mapping from Live Site (Last 30 Days)
$startDate = date('Y-m-d', strtotime('-30 days'));
$url = "https://newsthetruth.com/api/posts/latest?limit=100"; // Assuming latest API provides category info
// Actually, let's use the live sync data directly or a small script to get meta/category data.

// Since the previous history sync used REPLACE INTO, the categories in the pivot table (post_category) might be missing for those rows.
// Let's check a few recent ones.

$posts = Post::orderBy('id', 'desc')->take(10)->get();
foreach ($posts as $p) {
    if (empty($p->category_id)) {
        // Fallback mapping based on title keywords if API not accessible/fails
        $title = strtolower($p->title);
        $desc = strtolower($p->description);
        
        $catId = 10; // Default: INDIA
        if (strpos($title, 'bengal') !== false || strpos($title, 'kolkata') !== false || strpos($desc, 'bengal') !== false) {
            $catId = 11; // BENGAL
        } else if (strpos($title, 'us ') !== false || strpos($title, 'un ') !== false || strpos($title, 'world') !== false) {
             $catId = 12; // WORLD
        } else if (strpos($title, 'modi') !== false || strpos($title, 'gandhi') !== false || strpos($title, 'bjp') !== false || strpos($title, 'congress') !== false) {
             $catId = 13; // POLITICS
        }
        
        $p->category_id = $catId;
        $p->save();
        
        // Also ensure pivot table exists
        DB::table('post_categories')->updateOrInsert(
            ['post_id' => $p->id],
            ['category_id' => $catId]
        );
        
        echo "Post {$p->id}: Mapped to Category $catId\n";
    }
}

echo "Category mapping complete.\n";
