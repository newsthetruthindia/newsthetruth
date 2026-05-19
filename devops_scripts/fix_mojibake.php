<?php
$content = file_get_contents('sync_utf8.txt');
// Standard mojibake fix: treat as ISO-8859-1 and re-encode to UTF-8
// But wait, the content IS UTF-8 but the characters are multi-byte sequences interpreted as individual chars.
// So we want to treat it as Latin-1 and convert to UTF-8.
$fixed = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');
file_put_contents('sync_utf8_fixed.sql', $fixed);
echo "Fixed saved to sync_utf8_fixed.sql\n";
