<?php
// fix_vps_mojibake_v6.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

ini_set('memory_limit', '1024M');

echo "Starting Residue Cleanup (v6) on table: posts...\n";

$replacements = [
    "\xE2\x80\x9D\xC2\xA6" => "\xE2\x80\x9D", // ”¦ -> ”
    "\xE2\x80\x99\xC2\xA6" => "\xE2\x80\x99", // ’¦ -> ’
    "\xC2\xA6"             => "...",           // ¦ -> ... (Safest for ellipsis residue)
    "â€"                   => "”",
    "â€¦"                  => "...",
];

$advancedFix = function($str) use ($replacements) {
    if ($str === null || $str === '') return $str;
    $fixed = str_replace(array_keys($replacements), array_values($replacements), $str);
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
            echo "Cleaning Post #{$post->id} - New: " . mb_substr($new_title, 0, 80) . "...\n";
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
