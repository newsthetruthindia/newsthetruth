<?php
$dbHost = "127.0.0.1"; $dbName = "newstew1_main"; $dbUser = "newstew1_newsthet"; $dbPass = "3RdX?tPig*^$";
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    echo "--- POSTS TABLE COLUMNS ---\n";
    foreach ($pdo->query("DESCRIBE posts")->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
    echo "\n--- USERS/ROLES CHECK ---\n";
    foreach ($pdo->query("SHOW TABLES LIKE '%role%'")->fetchAll(PDO::FETCH_NUM) as $row) {
        echo "Table: " . $row[0] . "\n";
    }
} catch (Exception $e) { echo $e->getMessage(); }
?>
