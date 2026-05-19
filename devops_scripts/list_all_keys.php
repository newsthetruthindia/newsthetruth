<?php
$dbHost = "127.0.0.1"; $dbName = "newstew1_main"; $dbUser = "newstew1_newsthet"; $dbPass = "3RdX?tPig*^$";
$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
$keys = $pdo->query("SELECT DISTINCT `key` FROM post_metas ORDER BY `key` ASC")->fetchAll(PDO::FETCH_ASSOC);
foreach ($keys as $k) { echo "Key: " . $k['key'] . "\n"; }
?>
