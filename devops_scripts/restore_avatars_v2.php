<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// These are the real avatar files found in the storage directory
$avatars = [
    9   => 'uploads/avatars/01KN1GATTJY81RABGRPJ29D3T4.jpg', // Titas
    385 => 'uploads/avatars/01KN1HF5HK4PH8SG1PF75X3RP9.jpg', // Dipaneeta
    2   => 'uploads/avatars/01KN1K8S8KQNK4VS6THJR8VSA1.jpg', // Rony
];

echo "Starting Avatar Restoration...\n";

foreach ($avatars as $userId => $relPath) {
    // 1. Re-link or create the media entry
    $mediaId = DB::table('medias')->where('url', $relPath)->value('id');
    
    if (!$mediaId) {
        $mediaId = DB::table('medias')->insertGetId([
            'type' => 'image',
            'path' => $relPath,
            'url'  => $relPath,
            'mimetype' => 'image/jpeg',
            'extension' => 'jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "   - Re-registered media record ID $mediaId for $relPath\n";
    }

    // 2. Update user_details
    DB::table('user_details')->where('user_id', $userId)->update(['attachment_id' => $mediaId]);
    echo "   - User #$userId now linked to avatar ID $mediaId\n";
}

echo "AVATAR RESTORATION COMPLETE.\n";
?>
