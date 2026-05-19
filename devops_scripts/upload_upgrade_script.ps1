$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"
$file = "d:\NTT_WEBSITE\upgrade_backend_vps.sh"

Write-Host "Uploading $file..." -ForegroundColor Cyan
scp -i $sshKey -o StrictHostKeyChecking=no $file "${vpsUser}@${vpsHost}:/var/www/ntt/"
Write-Host "Uploaded $file successfully." -ForegroundColor Green
