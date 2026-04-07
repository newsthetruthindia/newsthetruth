<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Post;
use App\Models\User;

$users = User::all();
$userMap = [];
foreach ($users as $u) {
    $fullName = strtolower(trim($u->firstname . ' ' . $u->lastname));
    $userMap[$fullName] = $u->id;
}
$aliasMap = [
    'ntt desk' => 0,
    'ntt staff' => 0,
    'staff reporter' => 0,
    'titas' => 9,
    'sonakshi ghosh' => 391,
];

// Fetch recent 100 posts from live API to get the correct credit metadata
$response = file_get_contents('https://newsthetruth.com/api/posts/latest?limit=100');
$json = json_decode($response, true);
$apiPosts = $json['data']['data'] ?? ($json['data'] ?? []);

$affected = 0;

foreach ($apiPosts as $apiPost) {
    if (!isset($apiPost['id'])) continue;
    
    $localPost = Post::find($apiPost['id']);
    if (!$localPost) continue;

    $reporterName = null;
    $credit = $apiPost['image_credit'] ?? '';
    
    if (!empty($credit) && strtolower(trim($credit)) !== 'ntt desk') {
        $reporterName = trim($credit);
    }
    
    if (!$reporterName && !empty($apiPost['reporter_name']) && strtolower(trim($apiPost['reporter_name'])) !== 'ntt desk') {
        $reporterName = trim($apiPost['reporter_name']);
    }

    if ($reporterName) {
        $cleanName = preg_replace('/^(By\s*-?\s*|Source\s*-?\s*|Credit\s*-?\s*)/i', '', $reporterName);
        $cleanName = ucwords(strtolower(trim($cleanName)));
        
        $searchName = strtolower($cleanName);
        $newUserId = $aliasMap[$searchName] ?? ($userMap[$searchName] ?? 0);

        if ($localPost->user_id !== $newUserId || $localPost->reporter_name !== $cleanName) {
            $localPost->user_id = $newUserId;
            $localPost->reporter_name = $cleanName;
            $localPost->timestamps = false;
            $localPost->save();
            $affected++;
            echo "Fixed: {$localPost->id} -> $cleanName ($newUserId)\n";
        }
    }
}
echo "Total recent posts recovered: $affected\n";

