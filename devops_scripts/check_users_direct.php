<?php
// Since I can't easily run Laravel in this shell without vendor, I'll use a direct DB query if I have credentials.
// But I saw reassign_arko.php already using 183 for Arko Saha.

$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'newstew1_main';
$dbUser = 'newstew1_newsthet';
$dbPass = '3RdX?tPig*^$';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
if ($mysqli->connect_error) die("Connection failed");

$res = $mysqli->query("SELECT id, firstname, lastname FROM users WHERE firstname LIKE 'Rony%' OR firstname LIKE 'Arko%'");
while($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . " Name: " . $row['firstname'] . " " . $row['lastname'] . "\n";
}
$mysqli->close();
