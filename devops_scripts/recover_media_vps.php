<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

echo "=== STARTING MEDIA PATH CLEANING & RECOVERY ===\n";

// 1. Path Cleaning
$prefix = '/home2/newstew1/public_html/public/';
$affected = DB::table('medias')
    ->where('path', 'LIKE', $prefix . '%')
    ->orWhere('url', 'LIKE', $prefix . '%')
    ->get();

echo "Found " . count($affected) . " records with cPanel absolute paths.\n";

foreach ($affected as $m) {
    $cleanPath = str_replace($prefix, '', $m->path);
    $cleanUrl = str_replace($prefix, '', $m->url);
    
    DB::table('medias')->where('id', $m->id)->update([
        'path' => $cleanPath,
        'url' => $cleanUrl
    ]);
    // echo "  Fixed ID {$m->id}: $cleanPath\n";
}

echo "Database cleaning complete.\n";

// 2. Media Recovery (Downloads)
$recentMedias = Media::orderBy('id', 'desc')->take(200)->get();
$downloaded = 0;
$sourceBase = "https://newsthetruth.com/public/";

foreach ($recentMedias as $media) {
    if (empty($media->path)) continue;
    
    $localPath = $media->path;
    if (!Storage::disk('public')->exists($localPath)) {
        echo "  Missing locally: $localPath\n";
        $remoteUrl = $sourceBase . $localPath;
        
        $content = @file_get_contents($remoteUrl);
        if ($content) {
            Storage::disk('public')->put($localPath, $content);
            echo "    [SUCCESS] Downloaded: $localPath\n";
            $downloaded++;
        } else {
            echo "    [ERROR] Could not fetch from: $remoteUrl\n";
        }
    }
}

echo "\n=== MEDIA RECOVERY COMPLETE ===\n";
echo "Total Files Downloaded: $downloaded\n";
