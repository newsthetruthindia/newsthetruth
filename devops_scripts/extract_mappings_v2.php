<?php
$inputFile = "d:/NTT_WEBSITE/backups/database_backup.sql";
$outputFile = "d:/NTT_WEBSITE/full_mappings_base.sql";
$targetTables = ["categories", "tags", "post_categories", "post_tags", "medias", "user_roles", "role_permissions"];
$in = fopen($inputFile, "r");
$out = fopen($outputFile, "w");
if ($in && $out) {
    while (($line = fgets($in)) !== false) {
        foreach ($targetTables as $table) {
            if (strpos($line, "INSERT INTO `$table`") !== false) {
                fwrite($out, $line);
                break;
            }
        }
    }
    fclose($in);
    fclose($out);
    echo "EXTRACTION_SUCCESS\n";
} else { echo "ERROR_OPENING_FILES\n"; }
?>
