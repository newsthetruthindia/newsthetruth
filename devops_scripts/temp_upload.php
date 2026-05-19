<?php
$ftp_server = "newsthetruth.com";
$ftp_user_name = "newstew1";
$ftp_user_pass = "G7r@9T!mQ*";

$local_file = 'd:\\NTT_WEBSITE\\backups\\migrate_range.php';
$remote_file = 'public_html/migrate_range.php';

echo "Connecting to $ftp_server...\n";
$conn_id = ftp_connect($ftp_server);
if (!$conn_id) die("Could not connect to $ftp_server\n");

$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
if (!$login_result) die("FTP Login Failed\n");

ftp_pasv($conn_id, false);

echo "Uploading $local_file to $remote_file...\n";
if (ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
    echo "Successfully uploaded migrate_range.php\n";
} else {
    echo "Error uploading file\n";
    ftp_close($conn_id);
    exit(1);
}

ftp_close($conn_id);

echo "Triggering migration script via HTTP...\n";
$url = "https://newsthetruth.com/migrate_range.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 1200);
$output = curl_exec($ch);
if (curl_errno($ch)) {
    echo "cURL error: " . curl_error($ch) . "\n";
} else {
    echo "Execution output:\n" . strip_tags($output) . "\n";
}
curl_close($ch);
?>
