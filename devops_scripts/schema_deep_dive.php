<?php
ob_start();
$dbHost = "127.0.0.1"; $dbName = "newstew1_main"; $dbUser = "newstew1_newsthet"; $dbPass = "3RdX?tPig*^$";
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    echo "--- POSTS TABLE COLUMNS ---\n";
    foreach ($pdo->query("DESCRIBE posts")->fetchAll(PDO::FETCH_ASSOC) as $row) { print_r($row); }
    echo "\n--- USERS TABLE COLUMNS ---\n";
    foreach ($pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_ASSOC) as $row) { print_r($row); }
    echo "\n--- ROLE TABLES CHECK ---\n";
    foreach ($pdo->query("SHOW TABLES LIKE '%role%'")->fetchAll(PDO::FETCH_NUM) as $row) {
        echo "Table: {$row[0]}\n";
        foreach ($pdo->query("DESCRIBE `{$row[0]}`")->fetchAll(PDO::FETCH_ASSOC) as $r) { print_r($r); }
    }
} catch (Exception $e) { echo "ERROR: " . $e->getMessage(); }
$data = ob_get_clean();
file_put_contents("schema_results.txt", $data);
echo "SCHEMA_DONE";
?>
