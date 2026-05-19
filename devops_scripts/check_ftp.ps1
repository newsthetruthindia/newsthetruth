# check_ftp.ps1
$php = "C:\Users\HPi9\AppData\Local\Programs\Local\resources\extraResources\lightning-services\php-8.2.27+1\bin\win64\php.exe"
$code = @'
$ftp_server = "newsthetruth.com";
$ftp_user_name = "newstew1";
$ftp_user_pass = "G7r@9T!mQ*";
$conn_id = ftp_connect($ftp_server);
ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
ftp_pasv($conn_id, true);
$files = ftp_nlist($conn_id, "public_html");
print_r($files);
$files2 = ftp_nlist($conn_id, "public_html/public");
print_r($files2);
ftp_close($conn_id);
'@

& $php -r $code
