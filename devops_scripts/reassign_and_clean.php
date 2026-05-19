<?php
$content = file_get_contents('sync_utf8.txt');

// 1. Reassign IDs (Arko 183 -> 0, Rony 385 -> 0)
// We look for patterns like , '183', '183', '183', or ('4160', '385', '385', '385',
// The structure seems to be VALUES ('post_id', 'user_id', 'published_by', 'reviewed_by', ...)
$content = preg_replace("/VALUES \('(\d+)', '183', '183', '183',/", "VALUES ('$1', '0', '183', '183',", $content);
$content = preg_replace("/VALUES \('(\d+)', '385', '385', '385',/", "VALUES ('$1', '0', '385', '385',", $content);

// 2. Fix Mojibake (Direct replacements for common patterns)
$fixes = [
    'â€œ' => '“',
    'â€ ' => '”',
    'â€˜' => '‘',
    'â€™' => '’',
    'â€”' => '—',
    'â€“' => '–',
    'â€¦' => '…',
    'â€' => '”', // fallback for broken sequences
    'Â ' => ' ',
    'Ã¢â‚¬â„¢' => '’',
    'Ã¢â‚¬Å“' => '“',
    'Ã¢â‚¬Â ' => '”',
    'Ã¢â‚¬â€œ' => '–',
    'Ã¢â‚¬â€' => '—',
    'Ã¢â‚¬Â¦' => '…'
];

foreach ($fixes as $bad => $good) {
    $content = str_replace($bad, $good, $content);
}

file_put_contents('ntt_final_sync.sql', $content);
echo "Final clean SQL saved to ntt_final_sync.sql\n";
echo "Reassigned Arko Saha (183) and Rony Santra (385) to NTT Desk (0) where they were authors.\n";
