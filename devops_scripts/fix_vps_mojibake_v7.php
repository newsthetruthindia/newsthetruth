<?php
// fix_vps_mojibake_v7.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

ini_set('memory_limit', '1024M');

echo "Starting Deep-Sanitizer (v7) on table: posts...\n";

$replacements = [
    "\xE2\x80\x9D\xC2\x9D" => "\xE2\x80\x9D", // Clean up ”􀀀 (residue byte)
    "\xE2\x80\x9C\xC2\x9D" => "\xE2\x80\x9C", // Clean up “􀀀
    "\xE2\x80\x99\xC2\x9D" => "\xE2\x80\x99", // Clean up ’􀀀
    "\xEF\xBF\xBD"         => "'",             // Replace UTF-8 error char ï¿½ with ' (common fallback)
    "\xC2\x9D"             => "",              // Global strip of the invisible control char
    "\xC2\x80"             => "",              // Global strip of padding/ghost bytes
];

$advancedFix = function($str) use ($replacements) {
    if ($str === null || $str === '') return $str;
    
    // Pass 1: Strip invisible garbage bytes first
    $fixed = str_replace(array_keys($replacements), array_values($replacements), $str);
    
    // Pass 2: Final cleanup of any double-quotes that became triplets
    $fixed = str_replace(["”’", "””", "’¦"], ["”", "”", "’"], $fixed);
    
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
            echo "Sanitizing Post #{$post->id} - Fixed hidden artifacts...\n";
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
    echo "Processed 100 posts (Total sanitized: $updateCount)...\n";
});

echo "SANITIZATION COMPLETE (Total fixed: $updateCount)\n";
?>
