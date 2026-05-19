<?php
// fix_vps_mojibake_v2.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

ini_set('memory_limit', '1024M');

echo "Starting Refined Mojibake Repair (v2) on table: posts...\n";

$updateCount = 0;

DB::table('posts')->chunkById(100, function ($posts) use (&$updateCount) {
    foreach ($posts as $post) {
        $corrupted = false;
        $title = $post->title;
        $description = $post->description;
        $excerpt = $post->excerpt;

        // Function for safe mojibake repair
        $safeFix = function($str) use (&$corrupted) {
            if ($str === null || $str === '') return $str;
            
            // Look for the byte sequence of 'â' which is ubiquitous in double-encoded UTF-8
            if (mb_strpos($str, 'â') !== false) {
                // Attempt to reverse the mangling by converting FROM UTF-8 TO ISO-8859-1
                $candidate = @mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
                
                // IMPORTANT SAFETY CHECK:
                // 1. Candidate must be valid UTF-8.
                // 2. Candidate must NOT be empty.
                // 3. Candidate must be different from original.
                // Legitimate accented characters like 'â' in names will become invalid UTF-8 (E2) 
                // and thus fail this check, preventing corruption.
                if ($candidate !== false && $candidate !== $str && mb_check_encoding($candidate, 'UTF-8')) {
                    $corrupted = true;
                    return $candidate;
                }
            }
            return $str;
        };

        $new_title = $safeFix($title);
        $new_desc = $safeFix($description);
        $new_excerpt = $safeFix($excerpt);

        if ($corrupted) {
            $updateCount++;
            echo "Repairing Post #{$post->id} - New Title: " . mb_substr($new_title, 0, 50) . "...\n";
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
