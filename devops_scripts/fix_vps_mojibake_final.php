<?php
// fix_vps_mojibake.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Starting Mojibake Repair on table: posts...\n";

DB::table('posts')->chunkById(100, function ($posts) {
    foreach ($posts as $post) {
        $corrupted = false;
        $title = $post->title;
        $description = $post->description;
        $excerpt = $post->excerpt;

        // Function to fix mojibake (double-encoded UTF-8)
        $fix = function($str) use (&$corrupted) {
            if ($str === null || $str === '') return $str;
            
            // If it contains the tell-tale signs of mojibake
            if (mb_strpos($str, 'â€') !== false || mb_strpos($str, 'â€˜') !== false || mb_strpos($str, 'â€™') !== false || mb_strpos($str, 'â€“') !== false) {
                $target = @mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
                // Check if the fixed string is valid UTF-8
                if ($target !== false && $target !== $str && mb_check_encoding($target, 'UTF-8')) {
                    $corrupted = true;
                    return $target;
                }
            }
            return $str;
        };

        $new_title = $fix($title);
        $new_desc = $fix($description);
        $new_excerpt = $fix($excerpt);

        if ($corrupted) {
            echo "Repairing Post #{$post->id} - Title: " . mb_substr($new_title, 0, 30) . "...\n";
            DB::table('posts')
                ->where('id', $post->id)
                ->update([
                    'title' => $new_title,
                    'description' => $new_desc,
                    'excerpt' => $new_excerpt,
                    'updated_at' => now(), // Keep track of fixed ones
                ]);
        }
    }
    echo "Processed a chunk of 100 posts...\n";
});

echo "REPAIR COMPLETE\n";
?>
