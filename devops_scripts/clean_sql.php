<?php
$inputFile = "d:/NTT_WEBSITE/all_mappings_merged.sql";
$outputFile = "d:/NTT_WEBSITE/all_mappings_cleaned.sql";
$in = fopen($inputFile, "r");
$out = fopen($outputFile, "w");
if ($in && $out) {
    while (($line = fgets($in)) !== false) {
        if (stripos($line, "INSERT INTO") !== false) {
            fwrite($out, $line);
        }
    }
    fclose($in);
    fclose($out);
    echo "CLEAN_COMPLETE";
} else { echo "ERROR"; }
?>
