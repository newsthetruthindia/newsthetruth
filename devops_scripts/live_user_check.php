<?php
$dbHost = "127.0.0.1"; $dbName = "newstew1_main"; $dbUser = "newstew1_newsthet"; $dbPass = "3RdX?tPig*^$";
$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
$users = $pdo->query("SELECT firstname, lastname, email, type FROM users WHERE type IN ('admin', 'employee') LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
print_r($users);
?>
