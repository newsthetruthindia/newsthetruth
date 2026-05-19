<?php
// restore_missing_media.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

ini_set('memory_limit', '1024M');

$medias = DB::table('medias')->where('id', '>=', 5800)->get();
$sourceBase = "https://newsthetruth.com/";

echo "Starting Media Restoration for IDs 5800+...\n";

foreach ($medias as $media) {
    // 1. Clean the URL (remove 'public/' prefix from source database dump error)
    $cleanUrl = str_replace("public/", "", $media->url);
    $fullSourceUrl = $sourceBase . "public/" . $cleanUrl;
    
    echo "Processing Media #{$media->id}: $cleanUrl\n";
    
    // 2. Download from Source
    $content = @file_get_contents($fullSourceUrl);
    if ($content) {
        // 3. Save to Local Storage
        Storage::disk('public')->put($cleanUrl, $content);
        echo "   - Downloaded and saved to storage: $cleanUrl\n";
        
        // 4. Update Database with correct relative paths
        DB::table('medias')
            ->where('id', $media->id)
            ->update([
                'url'  => $cleanUrl,
                'path' => $cleanUrl,
                'updated_at' => now(),
            ]);
        echo "   - Database updated.\n";
    } else {
        echo "   - ERROR: Could not download from $fullSourceUrl\n";
    }
}

echo "MEDIA RESTORATION COMPLETE\n";
?>
