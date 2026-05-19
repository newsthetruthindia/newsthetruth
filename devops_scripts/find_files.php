<?php
// find_files.php
$ftp_server = "newsthetruth.com";
$ftp_user_name = "newstew1";
$ftp_user_pass = "G7r@9T!mQ*";
$local_file = 'd:/NTT_WEBSITE/sync_manifest.txt';

$conn_id = ftp_connect($ftp_server);
if (!$conn_id) die("FTP Connection Failed.\n");
if (!ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)) die("FTP Login Failed.\n");
ftp_pasv($conn_id, true);

function list_recursive($conn_id, $dir) {
    $files = ftp_nlist($conn_id, $dir);
    if (!$files) return [];
    $result = [];
    foreach ($files as $file) {
        $result[] = $file;
    }
    return $result;
}

$manifest = [];
$manifest['root'] = ftp_nlist($conn_id, ".");
$manifest['public_html'] = ftp_nlist($conn_id, "public_html");
$manifest['public_html/public'] = ftp_nlist($conn_id, "public_html/public");

file_put_contents($local_file, print_r($manifest, true));
echo "Sync manifest created locally.\n";
ftp_close($conn_id);
?>
