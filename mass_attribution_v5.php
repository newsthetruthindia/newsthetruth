<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== STARTING MASS ATTRIBUTION RESTORATION (v5) ===\n";

// Load all users into memory for fast lookup
$users = User::all();
$userMap = [];
foreach ($users as $u) {
    $fullName = strtolower(trim($u->firstname . ' ' . $u->lastname));
    $userMap[$fullName] = $u->id;
}

// Special mappings and aliases
$aliasMap = [
    'ntt desk' => 0,
    'ntt staff' => 0,
    'staff reporter' => 0,
    'live update' => 0,
    'titas' => $userMap['titas mukherjee'] ?? 9,
    'sonakshi ghosh' => $userMap['soonakshi ghosh'] ?? 391,
];

$count = Post::count();
echo "Processing $count posts...\n";

$affected = 0;
Post::chunk(100, function ($posts) use (&$affected, $userMap, $aliasMap) {
    foreach ($posts as $p) {
        $reporterName = null;
        $foundSource = null;

        // 1. Check Metadata (highest priority)
        foreach ($p->metas as $m) {
            $key = strtolower($m->key);
            if (in_array($key, ['credit', 'source', 'author', 'writer', 'reporter'])) {
                if (!empty(trim($m->description)) && strtolower(trim($m->description)) !== 'ntt desk') {
                    $reporterName = trim($m->description);
                    $foundSource = "Meta: $key";
                    break;
                }
            }
        }

        // 2. Check Title Suffix
        if (!$reporterName) {
            if (preg_match('/\|\s*([A-Za-z\s]{5,40})$/i', $p->title, $matches)) {
                $reporterName = trim($matches[1]);
                $foundSource = "Title Suffix";
            }
        }

        // 3. Check Body Signature
        if (!$reporterName) {
            $body = strip_tags($p->description);
            if (preg_match('/By\s*-\s*([A-Za-z\s]{5,30})[\r\n\s]/i', substr($body, 0, 500), $matches)) {
                $reporterName = trim($matches[1]);
                $foundSource = "Body Start Signature";
            } elseif (preg_match('/By\s*-\s*([A-Za-z\s]{5,30})[\r\n\s]/i', substr($body, -500), $matches)) {
                $reporterName = trim($matches[1]);
                $foundSource = "Body End Signature";
            }
        }

        // Use existing reporter_name if nothing else found
        if (!$reporterName && !empty($p->reporter_name)) {
            $reporterName = $p->reporter_name;
            $foundSource = "Existing field";
        }

        // Default to NTT Desk if still nothing
        if (!$reporterName) {
            $reporterName = "NTT Desk";
            $foundSource = "Default";
        }

        // Clean up common prefixes
        $cleanName = preg_replace('/^(By\s*-?\s*|Source\s*-?\s*|Credit\s*-?\s*)/i', '', $reporterName);
        $cleanName = ucwords(strtolower(trim($cleanName)));

        // Resolve to User ID
        $searchName = strtolower($cleanName);
        $newUserId = $aliasMap[$searchName] ?? ($userMap[$searchName] ?? 0);

        // Update if changed
        if ($p->reporter_name !== $cleanName || $p->user_id !== $newUserId) {
            $p->reporter_name = $cleanName;
            $p->user_id = $newUserId;
            $p->timestamps = false; // Prevent updated_at changes
            $p->save();
            $affected++;
            // echo "Post {$p->id} updated to [{$cleanName}] (ID: {$newUserId}) via $foundSource\n";
        }
    }
    echo ".";
});

echo "\n=== ATTRIBUTION SYNC COMPLETE ===\n";
echo "Total posts updated: $affected\n";
?>
 Simon
