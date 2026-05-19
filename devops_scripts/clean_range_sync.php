<?php
$filename = 'sync_march31_april2.sql';
$content = file_get_contents($filename);

// 1. Reassign Author IDs (Arko 183 -> 0, Rony 385 -> 0)
// The structure is VALUES ('id', 'user_id', 'published_by', 'reviewed_by', ...)
// We reassign the author (user_id) but keep the review/publish IDs if they are needed for history (or set to 0 too).
// User wants articles attributed to "NTT Desk" (0).

$content = preg_replace("/VALUES \('(\d+)', '183',/", "VALUES ('$1', '0',", $content);
$content = preg_replace("/VALUES \('(\d+)', '385',/", "VALUES ('$1', '0',", $content);

// 2. Final check for encoding (though subagent says it's clean, we'll ensure common ones are fixed if any)
$fixes = [
    'â€œ' => '“',
    'â€ ' => '”',
    'â€˜' => '‘',
    'â€™' => '’',
    'â€”' => '—',
    'Â ' => ' '
];

foreach ($fixes as $bad => $good) {
    if (strpos($content, $bad) !== false) {
        $content = str_replace($bad, $good, $content);
    }
}

file_put_contents('ntt_sync_march31_april2_clean.sql', $content);
echo "Cleaned SQL saved to ntt_sync_march31_april2_clean.sql\n";
echo "Range: March 31 - April 2\n";
echo "Remapped Authors (183, 385) to 0.\n";
