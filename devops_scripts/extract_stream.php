<?php
$inputFile = "d:/NTT_WEBSITE/backups/database_backup.sql";
$outputFile = "d:/NTT_WEBSITE/full_mappings_base.sql";
$targetTables = ["categories", "tags", "post_categories", "post_tags", "medias", "user_roles", "role_permissions"];
$in = fopen($inputFile, "r");
$out = fopen($outputFile, "w");
if ($in && $out) {
    $currentPos = 0;
    while (!feof($in)) {
        fseek($in, $currentPos);
        $start = fread($in, 1024);
        if ($start === false) break;
        $match = false;
        foreach ($targetTables as $table) {
            if (strpos($start, "INSERT INTO `$table`") !== false) {
                $match = true; break;
            }
        }
        if ($match) {
            fseek($in, $currentPos);
            while (!feof($in)) {
                $chunk = fread($in, 8192);
                if ($chunk === false) break;
                $nl = strpos($chunk, "\n");
                if ($nl !== false) {
                    fwrite($out, substr($chunk, 0, $nl + 1));
                    $currentPos += $nl + 1;
                    break;
                }
                fwrite($out, $chunk);
                $currentPos += strlen($chunk);
            }
        } else {
            fseek($in, $currentPos);
            while (!feof($in)) {
                $chunk = fread($in, 65536);
                if ($chunk === false) break;
                $nl = strpos($chunk, "\n");
                if ($nl !== false) {
                    $currentPos += $nl + 1;
                    break;
                }
                $currentPos += strlen($chunk);
            }
        }
    }
    fclose($in); fclose($out);
    echo "SUCCESS";
} else { echo "FAILED_OPEN"; }
?>
