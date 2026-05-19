<?php
$file = "d:/NTT_WEBSITE/backups/database_backup.sql";
$target = "INSERT INTO `medias`";
$buffer_size = 1024 * 1024; // 1MB

$handle = fopen($file, "rb");
if ($handle) {
    while (!feof($handle)) {
        $chunk = fread($handle, $buffer_size);
        $pos = strpos($chunk, $target);
        if ($pos !== false) {
            echo substr($chunk, $pos, 2000);
            break;
        }
    }
    fclose($handle);
}
