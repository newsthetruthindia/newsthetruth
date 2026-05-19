<?php
$host = '127.0.0.1';
$db   = 'newstew1_main';
$user = 'newstew1_newsthet';
$pass = '3RdX?tPig*^$'; // From .env
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$stmt = $pdo->query("SELECT * FROM medias WHERE id > 5682");
$rows = $stmt->fetchAll();

$sql = "";
foreach ($rows as $row) {
    $columns = implode(", ", array_keys($row));
    $values = array_map(function($val) use ($pdo) {
        if ($val === null) return "NULL";
        return $pdo->quote($val);
    }, array_values($row));
    $valuesStr = implode(", ", $values);
    $sql .= "INSERT INTO medias ($columns) VALUES ($valuesStr);\n";
}

file_put_contents("d:/NTT_WEBSITE/missing_medias.sql", $sql);
echo "Exported " . count($rows) . " rows to missing_medias.sql\n";
