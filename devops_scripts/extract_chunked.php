<?php
$inputFile = "d:/NTT_WEBSITE/backups/database_backup.sql";
$outputFile = "d:/NTT_WEBSITE/full_mappings_base.sql";
$targetTables = ["categories", "tags", "post_categories", "post_tags", "medias", "user_roles", "role_permissions"];

$in = fopen($inputFile, "r");
$out = fopen($outputFile, "w");

if ($in && $out) {
    while (!feof($in)) {
        // Read just the beginning of the line to check for INSERT INTO
        // If it matches, we read the whole line in chunks to output
        $pos = ftell($in);
        $start = fread($in, 1024);
        fseek($in, $pos); // Go back to start of line
        
        $match = false;
        foreach ($targetTables as $table) {
            if (strpos($start, "INSERT INTO `$table`") !== false) {
                $match = true;
                break;
            }
        }
        
        if ($match) {
            // Read until newline and write out
            while (($c = fread($in, 8192)) !== false) {
                $newlinePos = strpos($c, "\n");
                if ($newlinePos !== false) {
                    fwrite($out, substr($c, 0, $newlinePos + 1));
                    fseek($in, $pos + $newlinePos + 1); // Move pointer to next line start
                    break;
                }
                fwrite($out, $c);
                $pos += strlen($c);
                if (feof($in)) break;
            }
        } else {
            // Skip to next line
            fgets($in);
        }
    }
    fclose($in);
    fclose($out);
    echo "CHUNKED_EXTRACTION_COMPLETE\n";
} else { echo "ERROR\n"; }
?>
