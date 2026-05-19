<?php
$handle = fopen("d:/NTT_WEBSITE/backups/database_backup.sql", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        if (strpos($line, "INSERT INTO `medias`") !== false && strpos($line, "(5708,") !== false) {
            echo $line;
            break;
        }
        // Also look for values string format if it's a multi-insert
        if (strpos($line, "(5708,") !== false && (strpos($line, "INSERT INTO") === false)) {
             // This might be tricky if it's one giant insert.
        }
    }
    fclose($handle);
}
