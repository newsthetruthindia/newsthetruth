# check_ftp_v2.ps1
$php = "C:\Users\HPi9\AppData\Local\Programs\Local\resources\extraResources\lightning-services\php-8.2.27+1\bin\win64\php.exe"
$code = '$ftp_server = "newsthetruth.com"; $ftp_user_name = "newstew1"; $ftp_user_pass = "G7r@9T!mQ*"; $conn_id = ftp_connect($ftp_server); ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); ftp_pasv($conn_id, true); echo "--- public_html ---\n"; print_r(ftp_nlist($conn_id, "public_html")); echo "--- public_html/public ---\n"; print_r(ftp_nlist($conn_id, "public_html/public")); ftp_close($conn_id);'

& $php -r $code
