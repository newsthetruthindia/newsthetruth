<?php
// DB credentials from env_file.txt
$host = '127.0.0.1';
$db   = 'newstew1_main';
$user = 'newstew1_newsthet';
$pass = '3RdX?tPig*^$';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking 'posts' table schema:\n";
    $stmt = $pdo->query("DESCRIBE posts");
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        printf("%-20s | %-15s | %-5s | %-5s | %s\n", $row['Field'], $row['Type'], $row['Null'], $row['Key'], $row['Default']);
    }
    
    echo "\nChecking 'categories' table exists:\n";
    try {
        $pdo->query("SELECT 1 FROM categories LIMIT 1");
        echo "Table 'categories' exists.\n";
    } catch(Exception $e) {
        echo "Table 'categories' DOES NOT EXIST.\n";
    }

    echo "\nChecking 'filament_categories' table exists:\n";
    try {
        $pdo->query("SELECT 1 FROM filament_categories LIMIT 1");
        echo "Table 'filament_categories' exists.\n";
    } catch(Exception $e) {
        echo "Table 'filament_categories' DOES NOT EXIST.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
