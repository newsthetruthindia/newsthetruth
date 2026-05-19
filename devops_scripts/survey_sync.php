<?php
// survey_sync.php: Exports posts and media IDs for the March 28-30 range.

$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'newstew1_main';
$dbUser = 'newstew1_newsthet';
$dbPass = '3RdX?tPig*^$';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

$startDate = '2026-03-28 00:00:00';
$endDate = '2026-03-30 23:59:59';

echo "### POSTS ###\n";
$posts_query = "SELECT * FROM posts WHERE created_at BETWEEN '$startDate' AND '$endDate' OR updated_at BETWEEN '$startDate' AND '$endDate'";
$result = $mysqli->query($posts_query);
$new_posts = [];
$media_ids = [];

while ($row = $result->fetch_assoc()) {
    $new_posts[] = $row;
    if (!empty($row['thumbnail'])) $media_ids[] = $row['thumbnail'];
}

echo base64_encode(serialize($new_posts)) . "\n";

echo "### MEDIA ###\n";
if (!empty($media_ids)) {
    $ids_str = implode(',', array_unique($media_ids));
    $media_query = "SELECT * FROM media WHERE id IN ($ids_str)";
    $res = $mysqli->query($media_query);
    $media_data = [];
    while ($m = $res->fetch_assoc()) {
        $media_data[] = $m;
    }
    echo base64_encode(serialize($media_data)) . "\n";
}

$mysqli->close();
?>
