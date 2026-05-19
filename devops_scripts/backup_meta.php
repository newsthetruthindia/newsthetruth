<?php
// backup_meta.php
$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'newstew1_main';
$dbUser = 'newstew1_newsthet';
$dbPass = '3RdX?tPig*^$';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
$mysqli->set_charset("utf8mb4");

$startDate = $_GET['start'] ?? date('Y-m-d', strtotime('-45 days'));
$endDate = $_GET['end'] ?? date('Y-m-d');

if (strlen($startDate) <= 10) $startDate .= ' 00:00:00';
if (strlen($endDate) <= 10) $endDate .= ' 23:59:59';

// Get post IDs
$post_query = "SELECT id FROM posts WHERE (created_at BETWEEN '$startDate' AND '$endDate') OR (updated_at BETWEEN '$startDate' AND '$endDate')";
$res = $mysqli->query($post_query);
$post_ids = [];
while ($row = $res->fetch_assoc()) {
    $post_ids[] = $row['id'];
}

header('Content-Type: text/plain');

if (!empty($post_ids)) {
    $ids_str = implode(',', $post_ids);
    $meta_query = "SELECT * FROM postmeta WHERE post_id IN ($ids_str)";
    $res_m = $mysqli->query($meta_query);
    while ($m = $res_m->fetch_assoc()) {
        $cols = array_keys($m);
        $vals = array_map(function($val) use ($mysqli) {
            if ($val === null) return 'NULL';
            return "'" . $mysqli->real_escape_string($val) . "'";
        }, array_values($m));
        echo "REPLACE INTO postmeta (`" . implode("`, `", $cols) . "`) VALUES (" . implode(", ", $vals) . ");\n";
    }
}
$mysqli->close();
die();
?>
