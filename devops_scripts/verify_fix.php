<?php
$content = file_get_contents('sync_utf8.txt');
echo "Original size: " . strlen($content) . "\n";
echo "Snippet: " . substr($content, strpos($content, "Amit Shah") - 50, 200) . "\n";

// Strategy 1: Reverse UTF-8 to Latin1
$fixed1 = @mb_convert_encoding($content, 'ISO-8859-1', 'UTF-8');
$count1 = substr_count($fixed1, 'â');

// Strategy 2: More aggressive fix
$fixed2 = @iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $content);
$count2 = substr_count($fixed2, 'â');

// Strategy 3: Just treat as bytes
$fixed3 = $content; // Placeholder

echo "Counts of 'â' - Original: " . substr_count($content, 'â') . "\n";
echo "Count Strategy 1 (UTF-8 to Latin1): $count1\n";
echo "Count Strategy 2 (iconv): $count2\n";

if ($count1 < substr_count($content, 'â')) {
    echo "Strategy 1 helped! Snippet: " . substr($fixed1, strpos($fixed1, "Amit Shah") - 50, 200) . "\n";
    file_put_contents('sync_fixed_test.sql', $fixed1);
}
?>
