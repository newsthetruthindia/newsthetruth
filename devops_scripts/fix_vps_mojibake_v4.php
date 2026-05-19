<?php
// fix_vps_mojibake_v4.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

ini_set('memory_limit', '1024M');

echo "Starting Final Comprehensive Mojibake Repair (v4) on table: posts...\n";

// Translation table for common character patterns seen in the logs
$replacements = [
    "â€™" => "’",
    "â€˜" => "‘",
    "â€œ" => "“",
    "â€" => "”",
    "â€"  => "”",
    "â€¦" => "…",
    "â€“" => "–",
    "â€”" => "—",
    "â‚¹" => "₹",
    "â€¢" => "•",
];

$advancedFix = function($str) use ($replacements) {
    if ($str === null || $str === '') return $str;
    
    // Pass 1: String-based replacements for common sequences
    $fixed = str_replace(array_keys($replacements), array_values($replacements), $str);
    
    // Pass 2: Final fallback for any residual double-encoded sequences
    if (strpos($fixed, "â") !== false) {
        $candidate = @mb_convert_encoding($fixed, 'ISO-8859-1', 'UTF-8');
        if ($candidate !== false && $candidate !== $fixed && mb_check_encoding($candidate, 'UTF-8')) {
            $fixed = $candidate;
        }
    }
    
    return $fixed;
};

$updateCount = 0;

DB::table('posts')->chunkById(100, function ($posts) use ($advancedFix, &$updateCount) {
    foreach ($posts as $post) {
        $new_title = $advancedFix($post->title);
        $new_desc = $advancedFix($post->description);
        $new_excerpt = $advancedFix($post->excerpt);

        if ($new_title !== $post->title || $new_desc !== $post->description || $new_excerpt !== $post->excerpt) {
            $updateCount++;
            echo "Repairing Post #{$post->id} - Final Title: " . mb_substr($new_title, 0, 80) . "...\n";
            DB::table('posts')
                ->where('id', $post->id)
                ->update([
                    'title' => $new_title,
                    'description' => $new_desc,
                    'excerpt' => $new_excerpt,
                    'updated_at' => now(),
                ]);
        }
    }
    echo "Processed 100 posts (Total repaired: $updateCount)...\n";
});

echo "REPAIR COMPLETE (Total fixed: $updateCount)\n";
?>
