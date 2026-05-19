<?php
// test_v5_logic.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

$id = 4177;
$post = DB::table('posts')->where('id', $id)->first();
$str = $post->title;

echo "Before: $str\n";
echo "Hex Before: " . bin2hex($str) . "\n";

$replacements = [
    "\xC3\xA2\xE2\x82\xAC\xE2\x84\xA2" => "\xE2\x80\x99", // ’
    "\xC3\xA2\xE2\x82\xAC\xCB\x9C"     => "\xE2\x80\x98", // ‘
    "\xC3\xA2\xE2\x82\xAC\xC5\x93"     => "\xE2\x80\x9C", // “
    "\xC3\xA2\xE2\x82\xAC\xC2\x9D"     => "\xE2\x80\x9D", // ”
    "\xC3\xA2\xE2\x82\xAC\xC2\xA6"     => "\xE2\x80\xA6", // …
    "\xC3\xA2\xE2\x80\x9D"             => "\xE2\x80\x9D", // ”
    "\xC3\xA2\xE2\x80\xA6"             => "\xE2\x80\xA6", // …
];

$fixed = str_replace(array_keys($replacements), array_values($replacements), $str);

echo "After Pass 1: $fixed\n";
echo "Hex After Pass 1: " . bin2hex($fixed) . "\n";

$candidate = @mb_convert_encoding($fixed, 'ISO-8859-1', 'UTF-8');
if ($candidate !== $fixed && mb_check_encoding($candidate, 'UTF-8')) {
    $fixed = $candidate;
}

echo "Final: $fixed\n";
echo "Hex Final: " . bin2hex($fixed) . "\n";
?>
