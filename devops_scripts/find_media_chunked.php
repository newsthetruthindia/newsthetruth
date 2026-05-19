<?php
$file = "d:/NTT_WEBSITE/backups/database_backup.sql";
$target = "(5708,";
$buffer_size = 1024 * 1024; // 1MB

$handle = fopen($file, "rb");
if ($handle) {
    $overlap = "";
    while (!feof($handle)) {
        $chunk = fread($handle, $buffer_size);
        $search_chunk = $overlap . $chunk;
        
        $pos = strpos($search_chunk, $target);
        if ($pos !== false) {
            $start = max(0, $pos - 500);
            $end = min(strlen($search_chunk), $pos + 1000);
            echo substr($search_chunk, $start, $end - $start);
            break;
        }
        
        $overlap = substr($chunk, -strlen($target));
    }
    fclose($handle);
}
