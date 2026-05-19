# Security Deployment Script for NTT VPS
$apiPath = "d:\NTT_WEBSITE\temp_upgrade\api.php"
$nginxPath = "d:\NTT_WEBSITE\temp_upgrade\ntt.conf"
$vpsCommand = "d:\NTT_WEBSITE\vps_command.ps1"

# Step 1: Deploy API.php
$apiBase64 = [Convert]::ToBase64String([IO.File]::ReadAllBytes($apiPath))
Write-Host "Syncing Hardened API Routes..." -ForegroundColor Cyan
& $vpsCommand "echo '$apiBase64' | base64 -d > /var/www/ntt/routes/api.php"

# Step 2: Deploy Nginx.conf
$nginxBase64 = [Convert]::ToBase64String([IO.File]::ReadAllBytes($nginxPath))
Write-Host "Syncing Secure Nginx Config..." -ForegroundColor Cyan
& $vpsCommand "echo '$nginxBase64' | base64 -d > /etc/nginx/conf.d/ntt.conf"

# Step 3: Refresh Services
Write-Host "Reloading Server Services..." -ForegroundColor Cyan
& $vpsCommand "cd /var/www/ntt && php artisan route:cache && nginx -t && systemctl reload nginx"

Write-Host "Security Hardening Complete!" -ForegroundColor Green
