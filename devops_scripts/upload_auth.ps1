$user = "newstew1"
$pass = "G7r@9T!mQ*"
$client = New-Object System.Net.WebClient
$client.Credentials = New-Object System.Net.NetworkCredential($user, $pass)

Write-Host "Uploading ApiAuthController.php..."
$client.UploadFile("ftp://newsthetruth.com/public_html/app/Http/Controllers/ApiAuthController.php", "d:\NTT_LOCAL_SERVER\app\Http\Controllers\ApiAuthController.php")

Write-Host "Uploading api.php..."
$client.UploadFile("ftp://newsthetruth.com/public_html/routes/api.php", "d:\NTT_LOCAL_SERVER\routes\api.php")

Write-Host "Uploading cache setter..."
$client.UploadFile("ftp://newsthetruth.com/public_html/public/cache_clear.php", "d:\NTT_WEBSITE\cache_clear.php")

Write-Host "Uploads complete!"
