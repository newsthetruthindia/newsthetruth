<?php
/**
 * sync_all_attributions_v2.php
 * Resolves the mismatch between reporter_name (text) and user_id (record attribution).
 */

require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;
use App\Models\User;

// SETTINGS
$dryRun = false; // Set to false to actually commit changes

echo "--------------------------------------------------\n";
echo "Reporter Attribution Auditor & Syncer v2.0\n";
echo "--------------------------------------------------\n";
echo "Mode: " . ($dryRun ? "DRY RUN (No changes will be saved)" : "LIVE SYNC") . "\n\n";

// 1. Map known reporters
$reporters = User::role('Reporter')->get();
$nameMap = [];
foreach ($reporters as $reporter) {
    $fullName = trim($reporter->firstname . ' ' . $reporter->lastname);
    $nameMap[$fullName] = $reporter->id;
}

echo "Mapped " . count($nameMap) . " official reporters from database.\n";

// 2. Process published posts in chunks to avoid memory issues
$stats = [
    'total_checked' => 0,
    'mismatches_found' => 0,
    'already_correct' => 0,
    'no_matching_user' => 0
];
$changes = [];

Post::where('status', 'published')
    ->whereNotNull('reporter_name')
    ->where('reporter_name', '!=', '')
    ->chunk(200, function ($posts) use (&$nameMap, &$stats, &$changes, $dryRun) {
        foreach ($posts as $post) {
            $stats['total_checked']++;
            $currentId = $post->user_id;
            $targetName = trim($post->reporter_name);
            
            // Check if the name exists in our reporter map
            if (isset($nameMap[$targetName])) {
                $expectedId = $nameMap[$targetName];
                
                if ($currentId != $expectedId) {
                    $stats['mismatches_found']++;
                    if (count($changes) < 100) { // Limit stored changes for the report
                        $changes[] = [
                            'id' => $post->id,
                            'title' => substr($post->title, 0, 40) . "...",
                            'reporter_name' => $targetName,
                            'from_id' => $currentId,
                            'to_id' => $expectedId
                        ];
                    }
                    
                    if (!$dryRun) {
                        $post->user_id = $expectedId;
                        $post->save();
                    }
                } else {
                    $stats['already_correct']++;
                }
            } else {
                $stats['no_matching_user']++;
            }
        }
    });

echo "\nAudit Results:\n";
echo "Total Posts Checked: " . $stats['total_checked'] . "\n";
echo "Matches Already Correct: " . $stats['already_correct'] . "\n";
echo "Unrecognized Reporter Names: " . $stats['no_matching_user'] . " (e.g. guest or specific strings)\n";
echo "Mismatches Found: " . $stats['mismatches_found'] . " <--- IMPORTANT\n";

if ($stats['mismatches_found'] > 0) {
    echo "\nProposed Changes (Sample):\n";
    echo str_pad("Post ID", 10) . " | " . str_pad("Reporter Name", 20) . " | " . str_pad("From ID", 10) . " | " . "To ID" . "\n";
    echo "--------------------------------------------------------------------------------\n";
    foreach (array_slice($changes, 0, 30) as $change) {
        echo str_pad($change['id'], 10) . " | " . str_pad($change['reporter_name'], 20) . " | " . str_pad($change['from_id'], 10) . " | " . $change['to_id'] . "\n";
    }
    
    if (count($changes) > 30) {
        echo "... and " . (count($changes) - 30) . " more.\n";
    }
}

echo "\nDone.\n";
