<?php
$host = '127.0.0.1';
$db   = 'newstew1_main';
$user = 'root';
$pass = '$9T%Lk057bzu';
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

$valid = ["'Titas Mukherjee'", "'Ankit Salvi'", "'Tamal Saha'", "'Dipaneeta Das'", "'Staff Reporter'", "'Soonakshi Ghosh'", "'Suprav Banerjee'", "'Aniket Datta'", "'Sankha Subhra Das'", "'Ntt Desk'"];
$validStr = implode(',', $valid);

echo "Merging anomalies...\n";
$sql = "UPDATE posts SET reporter_name = 'Ntt Desk' WHERE reporter_name NOT IN ($validStr) OR reporter_name IS NULL OR reporter_name = 'NULL'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
echo "Affected rows: " . $stmt->rowCount() . "\n";
?>
