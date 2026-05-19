<?php
ob_start();
$dbHost = "127.0.0.1"; $dbName = "newstew1_main"; $dbUser = "newstew1_newsthet"; $dbPass = "3RdX?tPig*^$";
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    
    echo "--- POST_METAS KEYS ---\n";
    $keys = $pdo->query("SELECT DISTINCT `key` FROM post_metas ORDER BY id DESC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($keys as $k) { echo "Key: {$k['key']}\n"; }

    echo "\n--- SEARCHING FOR 'credit', 'location', 'reporter' IN ALL TABLES ---\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_NUM);
    foreach ($tables as $t) {
        $cols = $pdo->query("DESCRIBE `{$t[0]}`")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $c) {
            if (preg_match('/credit|location|reporter|author|source/i', $c['Field'])) {
                echo "Table: {$t[0]} | Field: {$c['Field']} | Type: {$c['Type']}\n";
            }
        }
    }
} catch (Exception $e) { echo "ERROR: " . $e->getMessage(); }
$data = ob_get_clean();
file_put_contents("meta_results_v2.txt", $data);
echo "META_V2_DONE";
?>
