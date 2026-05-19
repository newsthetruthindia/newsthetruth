<?php
$inputFile = "d:/NTT_WEBSITE/backups/database_backup.sql";
$outputFile = "d:/NTT_WEBSITE/full_mappings_base.sql";
$targetTables = ["categories", "tags", "post_categories", "post_tags", "medias"];
$handle = fopen($inputFile, "r");
$out = fopen($outputFile, "w");
if ($handle && $out) {
    while (($line = fgets($handle)) !== false) {
        foreach ($targetTables as $table) {
            if (strpos($line, "INSERT INTO `$table`") !== false) {
                fwrite($out, $line);
                break;
            }
        }
    }
    fclose($handle);
    fclose($out);
    echo "EXTRACTION_COMPLETE";
} else { echo "FILE_OPEN_FAILED"; }
?>
