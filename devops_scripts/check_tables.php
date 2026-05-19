<?php
// DB credentials from env_file.txt
$host = '127.0.0.1';
$db   = 'newstew1_main';
$user = 'newstew1_newsthet';
$pass = '3RdX?tPig*^$';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $tables = ['posts', 'categories', 'post_categories', 'filament_categories', 'post_categories_pivot'];
    
    foreach($tables as $table) {
        try {
            $pdo->query("SELECT 1 FROM $table LIMIT 1");
            echo "Table '$table' exists.\n";
        } catch(Exception $e) {
            echo "Table '$table' DOES NOT EXIST.\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
