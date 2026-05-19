$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"
$file = "d:\NTT_WEBSITE\ntt_ai_context.md"

Write-Host "Uploading $file..." -ForegroundColor Cyan
scp -i $sshKey -o StrictHostKeyChecking=no $file "${vpsUser}@${vpsHost}:/var/www/ntt/"
Write-Host "Uploaded $file successfully." -ForegroundColor Green
