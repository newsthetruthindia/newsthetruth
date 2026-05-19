<?php
$ftp_server = "117.252.16.132";
$ftp_username = "newstew1";
$ftp_userpass = "Rony@123#";

$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
$login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
ftp_pasv($ftp_conn, true);

$local_file = 'd:\NTT_WEBSITE\backup_meta.php';
$server_file = 'public_html/backup_meta.php';

if (ftp_put($ftp_conn, $server_file, $local_file, FTP_ASCII)) {
    echo "Successfully uploaded $local_file\n";
} else {
    echo "Error uploading $local_file\n";
}
ftp_close($ftp_conn);
?>
