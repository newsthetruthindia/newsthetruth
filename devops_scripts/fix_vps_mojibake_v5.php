<?php
// fix_vps_mojibake_v5.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

ini_set('memory_limit', '1024M');

echo "Starting Deep-Clean Mojibake Repair (v5) on table: posts...\n";

// Comprehensive PRIORITIZED translation table using exact hex sequences
// Using longer (more specific) sequences first is critical
$replacements = [
    "\xC3\xA2\xE2\x82\xAC\xE2\x84\xA2" => "\xE2\x80\x99", // ’
    "\xC3\xA2\xE2\x82\xAC\xCB\x9C"     => "\xE2\x80\x98", // ‘
    "\xC3\xA2\xE2\x82\xAC\xC5\x93"     => "\xE2\x80\x9C", // “
    "\xC3\xA2\xE2\x82\xAC\xC2\x9D"     => "\xE2\x80\x9D", // ” (common variant)
    "\xC3\xA2\xE2\x82\xAC\xC2\xA6"     => "\xE2\x80\xA6", // … (common variant)
    "\xC3\xA2\xE2\x80\x9D"             => "\xE2\x80\x9D", // ”
    "\xC3\xA2\xE2\x80\xA6"             => "\xE2\x80\xA6", // …
    "\xC3\xA2\xE2\x80\x93"             => "\xE2\x80\x93", // –
    "\xC3\xA2\xE2\x80\x94"             => "\xE2\x80\x94", // —
    "\xC3\xA2\xC2\x82\xC2\xB9"         => "\xE2\x82\xB9", // ₹
];

$advancedFix = function($str) use ($replacements) {
    if ($str === null || $str === '') return $str;
    
    // Pass 1: Direct multi-byte replacements
    $fixed = str_replace(array_keys($replacements), array_values($replacements), $str);
    
    // Pass 2: Safety pass for remaining â that form valid UTF-8 when reversed
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
            echo "Deep-Cleaning Post #{$post->id} - New: " . mb_substr($new_title, 0, 80) . "...\n";
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
