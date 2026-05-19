<?php
$file = "d:/NTT_WEBSITE/backups/database_backup.sql";
$target = "INSERT INTO `medias` VALUES ";
$buffer_size = 1024 * 1024; // 1MB

$handle = fopen($file, "rb");
if ($handle) {
    while (!feof($handle)) {
        $chunk = fread($handle, $buffer_size);
        $pos = strpos($chunk, $target);
        if ($pos !== false) {
            // Seek to the exact position and start reading the data
            fseek($handle, ftell($handle) - strlen($chunk) + $pos + strlen($target));
            
            $data = "";
            $collecting = true;
            $missing_rows = [];
            $current_id = 0;
            
            while ($collecting && !feof($handle)) {
                 $c = fgetc($handle);
                 if ($c === ";") {
                     $collecting = false;
                     break;
                 }
                 $data .= $c;
                 
                 // If we have enough data to parse some rows
                 if (strlen($data) > 10000) {
                      // Basic parsing of (id, ...)
                      preg_match_all('/\(([\d]+),/', $data, $matches, PREG_OFFSET_CAPTURE);
                      foreach ($matches[1] as $match) {
                           $id = (int)$match[0];
                           if ($id > 5682) {
                                // We found a candidate! 
                                // This parsing is complex because of string escaping.
                           }
                      }
                      $data = ""; // Reset or handle overlap
                 }
            }
            break;
        }
    }
    fclose($handle);
}
