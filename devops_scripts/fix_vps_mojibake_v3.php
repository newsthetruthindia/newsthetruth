<?php
// fix_vps_mojibake_v3.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

ini_set('memory_limit', '1024M');

echo "Starting Translation-Based Mojibake Repair (v3) on table: posts...\n";

// Translation table for common Mojibake sequences (Double-UTF8 via Windows-1252)
$replacements = [
    "\xC3\xA2\xE2\x82\xAC\xE2\x84\xA2" => "\xE2\x80\x99", // ’
    "\xC3\xA2\xE2\x82\xAC\xCB\x9C"     => "\xE2\x80\x98", // ‘
    "\xC3\xA2\xE2\x82\xAC\xC5\x93"     => "\xE2\x80\x9C", // “
    "\xC3\xA2\xE2\x82\xAC\xEF\xBF\xBD" => "\xE2\x80\x9D", // ” (sometimes)
    "\xC3\xA2\xE2\x82\xAC\x20\x1D"     => "\xE2\x80\x9D", // ”
    "\xC3\xA2\xE2\x82\xAC\xA6"         => "\xE2\x80\xA6", // …
    "\xC3\xA2\xE2\x82\xAC\x20\x13"     => "\xE2\x80\x93", // –
    "\xC3\xA2\xE2\x82\xAC\x20\x14"     => "\xE2\x80\x94", // —
    "\xC3\xA2\xE2\x80\x9A\xC2\xAC"     => "\xE2\x80\x99", // ’ alternative
    "\xC3\xA2\xC2\x82\xC2\xB9"         => "\xE2\x82\xB9", // ₹
];

// Fallback logic for any remaining â sequences if they look valid after conversion
$advancedFix = function($str) use ($replacements) {
    if ($str === null || $str === '') return $str;
    
    // 1. Direct replacements
    $fixed = str_replace(array_keys($replacements), array_values($replacements), $str);
    
    // 2. Generic repair for anything remaining with 'â'
    if (strpos($fixed, "\xC3\xA2") !== false) {
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
            echo "Repairing Post #{$post->id} - Fixed title: " . mb_substr($new_title, 0, 50) . "...\n";
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
