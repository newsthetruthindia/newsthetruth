<?php
// live_migrate_seo.php
$host = '127.0.0.1';
$db   = 'newstew1_main';
$user = 'newstew1_newsthet';
$pass = '3RdX?tPig*^$';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Adding 'meta_title' column...\n";
    try {
        $pdo->exec("ALTER TABLE posts ADD COLUMN meta_title VARCHAR(255) NULL AFTER subtitle");
        echo "Success.\n";
    } catch (Exception $e) {
        echo "Error or already exists: " . $e->getMessage() . "\n";
    }

    echo "Adding 'meta_description' column...\n";
    try {
        $pdo->exec("ALTER TABLE posts ADD COLUMN meta_description TEXT NULL AFTER meta_title");
        echo "Success.\n";
    } catch (Exception $e) {
        echo "Error or already exists: " . $e->getMessage() . "\n";
    }

    echo "Migration complete.\n";

} catch (Exception $e) {
    echo "Connection Error: " . $e->getMessage() . "\n";
}
?>
