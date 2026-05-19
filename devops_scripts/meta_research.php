<?php
ob_start();
$dbHost = "127.0.0.1"; $dbName = "newstew1_main"; $dbUser = "newstew1_newsthet"; $dbPass = "3RdX?tPig*^$";
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    
    echo "--- CATEGORIES PARENT CHECK ---\n";
    foreach ($pdo->query("DESCRIBE categories")->fetchAll(PDO::FETCH_ASSOC) as $row) { print_r($row); }
    
    echo "\n--- POST_METAS SCHEMA ---\n";
    foreach ($pdo->query("DESCRIBE post_metas")->fetchAll(PDO::FETCH_ASSOC) as $row) { print_r($row); }
    
    echo "\n--- SAMPLE METAS (Last 20) ---\n";
    foreach ($pdo->query("SELECT DISTINCT meta_key FROM post_metas ORDER BY id DESC LIMIT 50")->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo "Key: {$row['meta_key']}\n";
    }

    echo "\n--- LOCATION/REPORTER TABLES ---\n";
    $tables = $pdo->query("SHOW TABLES LIKE '%'")->fetchAll(PDO::FETCH_NUM);
    foreach ($tables as $t) {
        if (preg_match('/location|reporter|author|credit|source/i', $t[0])) {
            echo "Table: {$t[0]}\n";
            foreach ($pdo->query("DESCRIBE `{$t[0]}`")->fetchAll(PDO::FETCH_ASSOC) as $r) { print_r($r); }
        }
    }
} catch (Exception $e) { echo "ERROR: " . $e->getMessage(); }
$data = ob_get_clean();
file_put_contents("meta_results.txt", $data);
echo "META_DONE";
?>
