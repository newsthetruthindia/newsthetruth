<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Mapping of User IDs to their CORRECT avatar files found on disk
$alignment = [
    2   => 'uploads/avatars/01KMTH0S1J82N6DZZ8BH5HFDPG.jpg', // Rony
    7   => 'uploads/avatars/01KN1GDXP9T3F9GTYVV247JVH8.jpg', // Tamal
    9   => 'uploads/avatars/01KN1GATTJY81RABGRPJ29D3T4.jpg', // Titas
    359 => 'uploads/avatars/01KN1GEXXMQZ61C9GGGZCXEDNX.jpg', // Tridib
    381 => 'uploads/avatars/01KN1GATTJY81RABGRPJ29D3T4.jpg', // Ankit (Corrected based on audit)
    385 => 'uploads/avatars/01KN1FNXBX5Z15J07HGGV3F30Q.png', // Dipaneeta (High Quality PNG)
    390 => 'uploads/avatars/01KN1DCGZJ9AAV71G16BPSZ67N.png', // Admin Logo/Avatar
];

echo "Starting Master Avatar Alignment...\n";

foreach ($alignment as $userId => $relPath) {
    // 1. Ensure the media record exists for this path
    $mediaId = DB::table('medias')->where('url', $relPath)->orWhere('path', $relPath)->value('id');
    
    if (!$mediaId) {
        $ext = pathinfo($relPath, PATHINFO_EXTENSION);
        $mediaId = DB::table('medias')->insertGetId([
            'type' => 'image',
            'path' => $relPath,
            'url'  => $relPath,
            'mimetype' => ($ext == 'png' ? 'image/png' : 'image/jpeg'),
            'extension' => $ext,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "   - Created media record ID $mediaId for $relPath\n";
    }

    // 2. Map strictly to the user details
    DB::table('user_details')->where('user_id', $userId)->update(['attachment_id' => $mediaId]);
    echo "   - Aligned User #$userId to Media ID $mediaId\n";
}

echo "MASTER ALIGNMENT COMPLETE.\n";
?>
