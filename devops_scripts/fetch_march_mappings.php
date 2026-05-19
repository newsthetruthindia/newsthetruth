<?php
$dbHost = "127.0.0.1"; $dbName = "newstew1_main"; $dbUser = "newstew1_newsthet"; $dbPass = "3RdX?tPig*^$";
$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
$tableMappings = ["post_categories", "post_tags", "medias"];
$sql = "-- Targeted March Export\n";
foreach ($tableMappings as $table) {
    // For medias, we might need all if IDs shifted, but for mappings we target new posts
    $query = "SELECT * FROM $table"; 
    // Optimization: for post_categories/tags, we only need those for posts created after Mar 11
    if ($table != "medias") {
        $query .= " WHERE post_id IN (SELECT id FROM posts WHERE post_publish_time >= \"2026-03-12 00:00:00\")";
    } else {
        $query .= " WHERE created_at >= \"2026-03-12 00:00:00\"";
    }
    $rows = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        $cols = "`" . implode("`, `", array_keys($row)) . "`";
        $vals = array_map(fn($v) => $v === null ? "NULL" : $pdo->quote($v), array_values($row));
        $sql .= "REPLACE INTO `$table` ($cols) VALUES (" . implode(", ", $vals) . ");\n";
    }
}
file_put_contents("march_mappings.sql", $sql);
echo "DONE|" . filesize("march_mappings.sql");
?>
