<?php
// ftp_upload.php
$ftp_server = "newsthetruth.com";
$ftp_user_name = "newstew1";
$ftp_user_pass = "G7r@9T!mQ*";
$local_file = 'd:/NTT_WEBSITE/backup_range.php';
$remote_file = 'public_html/backup_range.php';

$conn_id = ftp_connect($ftp_server);
if (!$conn_id) die("FTP Connection Failed.\n");
if (!ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)) die("FTP Login Failed.\n");
ftp_pasv($conn_id, true);

if (ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
    echo "Successfully uploaded $local_file to $remote_file\n";
} else {
    echo "Error uploading $local_file\n";
}
ftp_close($conn_id);
?>
