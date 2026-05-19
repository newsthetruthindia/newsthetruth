<?php
// debug_one_post.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

$id = 4177;
$post = DB::table('posts')->where('id', $id)->first();

if (!$post) {
    die("Post $id not found.\n");
}

$str = $post->title;
echo "Original Title: $str\n";
echo "Hex dump: " . bin2hex($str) . "\n";

$candidate = @mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');
echo "Candidate: $candidate\n";
echo "Hex dump Candidate: " . bin2hex($candidate) . "\n";
echo "Candidate Valid UTF-8? " . (mb_check_encoding($candidate, 'UTF-8') ? 'Yes' : 'No') . "\n";
?>
