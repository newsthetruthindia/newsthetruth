$ftpServer = "ftp://117.252.16.132/"
$user = "newstew1"
$pass = "Rony@123#"
$webClient = New-Object System.Net.WebClient
$webClient.Credentials = New-Object System.Net.NetworkCredential($user, $pass)
$webClient.UploadFile($ftpServer + "public_html/backup_meta.php", "d:\NTT_WEBSITE\backup_meta.php")
Write-Host "Uploaded successfully!"
