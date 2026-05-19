<?php
$c = file_get_contents('sync_utf8.txt');
if (preg_match('/[\x{0980}-\x{09FF}]/u', $c)) {
    echo "Bengali characters found!\n";
} else {
    echo "No Bengali characters found.\n";
}
?>
