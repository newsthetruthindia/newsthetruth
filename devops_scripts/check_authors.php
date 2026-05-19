<?php
// check_missing_reporters.php - Find posts by specific reporters
$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'newstew1_main';
$dbUser = 'newstew1_newsthet';
$dbPass = '3RdX?tPig*^$';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

header('Content-Type: text/plain');

echo "=== ALL USERS WITH POST COUNTS ===\n";
$res = $mysqli->query("SELECT u.id, u.firstname, u.lastname, u.email, u.type, COUNT(p.id) as post_count FROM users u LEFT JOIN posts p ON p.user_id = u.id GROUP BY u.id, u.firstname, u.lastname, u.email, u.type ORDER BY post_count DESC");
while ($row = $res->fetch_assoc()) {
    echo "ID={$row['id']} | {$row['firstname']} {$row['lastname']} | {$row['email']} | type={$row['type']} | posts={$row['post_count']}\n";
}

echo "\n=== SEARCH: TAMAL SAHA ===\n";
$res = $mysqli->query("SELECT id, firstname, lastname, email FROM users WHERE firstname LIKE '%Tamal%' OR lastname LIKE '%Saha%'");
while ($row = $res->fetch_assoc()) {
    echo "User ID={$row['id']}: {$row['firstname']} {$row['lastname']} ({$row['email']})\n";
    $posts = $mysqli->query("SELECT id, title, status, created_at FROM posts WHERE user_id = {$row['id']} ORDER BY created_at DESC LIMIT 10");
    $count = $mysqli->query("SELECT COUNT(*) as c FROM posts WHERE user_id = {$row['id']}")->fetch_assoc()['c'];
    echo "  Total posts: $count\n";
    while ($p = $posts->fetch_assoc()) {
        echo "  Post #{$p['id']}: [{$p['status']}] {$p['title']} ({$p['created_at']})\n";
    }
}

echo "\n=== SEARCH: SOONAKSHI / SONAKSHI GHOSH ===\n";
$res = $mysqli->query("SELECT id, firstname, lastname, email FROM users WHERE firstname LIKE '%Soonakshi%' OR firstname LIKE '%Sonakshi%' OR lastname LIKE '%Ghosh%'");
while ($row = $res->fetch_assoc()) {
    echo "User ID={$row['id']}: {$row['firstname']} {$row['lastname']} ({$row['email']})\n";
    $posts = $mysqli->query("SELECT id, title, status, created_at FROM posts WHERE user_id = {$row['id']} ORDER BY created_at DESC LIMIT 10");
    $count = $mysqli->query("SELECT COUNT(*) as c FROM posts WHERE user_id = {$row['id']}")->fetch_assoc()['c'];
    echo "  Total posts: $count\n";
    while ($p = $posts->fetch_assoc()) {
        echo "  Post #{$p['id']}: [{$p['status']}] {$p['title']} ({$p['created_at']})\n";
    }
}

echo "\n=== SEARCH: ANKIT ===\n";
$res = $mysqli->query("SELECT id, firstname, lastname, email FROM users WHERE firstname LIKE '%Ankit%' OR firstname LIKE '%ankit%'");
while ($row = $res->fetch_assoc()) {
    echo "User ID={$row['id']}: {$row['firstname']} {$row['lastname']} ({$row['email']})\n";
    $posts = $mysqli->query("SELECT id, title, status, created_at FROM posts WHERE user_id = {$row['id']} ORDER BY created_at DESC LIMIT 10");
    $count = $mysqli->query("SELECT COUNT(*) as c FROM posts WHERE user_id = {$row['id']}")->fetch_assoc()['c'];
    echo "  Total posts: $count\n";
    while ($p = $posts->fetch_assoc()) {
        echo "  Post #{$p['id']}: [{$p['status']}] {$p['title']} ({$p['created_at']})\n";
    }
}

echo "\n=== TOTAL POSTS & USERS ON LIVE ===\n";
$total_posts = $mysqli->query("SELECT COUNT(*) as c FROM posts")->fetch_assoc()['c'];
$total_users = $mysqli->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
echo "Total posts: $total_posts\n";
echo "Total users: $total_users\n";

echo "\n=== ALL PUBLISHED_BY VALUES (to find hidden reporters) ===\n";
$res = $mysqli->query("SELECT p.published_by, u.firstname, u.lastname, COUNT(*) as cnt FROM posts p LEFT JOIN users u ON u.id = p.published_by GROUP BY p.published_by, u.firstname, u.lastname ORDER BY cnt DESC");
while ($row = $res->fetch_assoc()) {
    $name = $row['firstname'] ? "{$row['firstname']} {$row['lastname']}" : "UNKNOWN (ID={$row['published_by']})";
    echo "published_by={$row['published_by']} ($name): {$row['cnt']} posts\n";
}

$mysqli->close();
die();
?>
