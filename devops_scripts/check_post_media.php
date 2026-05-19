<?php
// DB credentials from env_file.txt
$host = '127.0.0.1';
$db   = 'newstew1_main';
$user = 'newstew1_newsthet';
$pass = '3RdX?tPig*^$';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Latest Post:\n";
    $stmt = $pdo->query("SELECT id, title, thumbnail, user_id FROM posts ORDER BY id DESC LIMIT 1");
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($post);
    
    if ($post && $post['thumbnail']) {
        echo "\nMedia record for thumbnail ID {$post['thumbnail']}:\n";
        $stmt = $pdo->prepare("SELECT * FROM medias WHERE id = ?");
        $stmt->execute([$post['thumbnail']]);
        print_r($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        echo "\nNo thumbnail ID found for the latest post.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
