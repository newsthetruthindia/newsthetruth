<?php
$host = '127.0.0.1';
$db   = 'newstew1_main';
$user = 'newstew1_newsthet';
$pass = '3RdX?tPig*^$';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SHOW COLUMNS FROM posts LIKE 'subtitle'");
    $column = $stmt->fetch();
    
    if ($column) {
        echo "Column 'subtitle' exists in 'posts' table.\n";
        print_r($column);
    } else {
        echo "Column 'subtitle' MISSING from 'posts' table.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
