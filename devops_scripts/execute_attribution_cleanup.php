<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;

echo "Starting Final Master Attribution Cleanup...\n";

// 1. Rename Ankit Salvi (User 381)
$ankit = User::find(381);
if ($ankit) {
    $ankit->update(['firstname' => 'Ankit', 'lastname' => 'Salvi']);
    
    // Restore his professional photo (ID 5807 was the random news photo)
    // Based on disk audit, ID 5829 or similar should be his.
    // I will use 5829 if it exists, or create a new one for 01KN1K9BP9E6F4KSZ0YG29FPSA.jpg
    $relPath = 'uploads/avatars/01KN1K9BP9E6F4KSZ0YG29FPSA.jpg';
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
    }
    DB::table('user_details')->where('user_id', 381)->update(['attachment_id' => $mediaId]);
    echo "   - User 381 renamed to Ankit Salvi and photo restored (Media ID $mediaId).\n";
}

// 2. Re-assign Soonakshi Ghosh (User 391)
$soonakshiCount = DB::table('posts')
    ->where(function($q) {
        $q->where('title', 'LIKE', '%Soonakshi%')
          ->orWhere('description', 'LIKE', '%Soonakshi%')
          ->orWhere('reporter_name', 'LIKE', '%Soonakshi%');
    })
    ->update(['user_id' => 391]);
echo "   - Re-assigned $soonakshiCount articles to Soonakshi Ghosh (User 391).\n";

// 3. Re-assign Tamal Saha (User 7)
$tamalCount = DB::table('posts')
    ->where(function($q) {
        $q->where('title', 'LIKE', '%Tamal Saha%')
          ->orWhere('description', 'LIKE', '%Tamal Saha%')
          ->orWhere('reporter_name', 'LIKE', '%Tamal Saha%');
    })
    ->update(['user_id' => 7]);
echo "   - Re-assigned $tamalCount articles to Tamal Saha (User 7).\n";

// 4. Publisher Purge (User 2 and User 193)
// Move ANY article currently assigned to publishers to NTT Desk (390)
$publisherCount = DB::table('posts')
    ->whereIn('user_id', [2, 193])
    ->update(['user_id' => 390]);
echo "   - Purged $publisherCount articles from Rony/Arko and moved to NTT Desk (ID 390).\n";

echo "CLEANUP COMPLETE.\n";
?>
