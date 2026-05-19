<?php
// DB credentials from env_file.txt
$host = '127.0.0.1';
$db   = 'newstew1_main';
$user = 'newstew1_newsthet';
$pass = '3RdX?tPig*^$';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Checking 'post_categories' table schema:\n";
    try {
        $stmt = $pdo->query("DESCRIBE post_categories");
        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            printf("%-20s | %-15s | %-5s | %-5s | %s\n", $row['Field'], $row['Type'], $row['Null'], $row['Key'], $row['Default']);
        }
    } catch(Exception $e) {
        echo "Error describing post_categories: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
