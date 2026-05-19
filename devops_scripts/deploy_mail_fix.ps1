# NTT Mail Fix + Admin Panel Verification Buttons Deployment
# Run this from d:\NTT_WEBSITE

Write-Host "=== NTT Mail Fix + Admin Verification Deployment ===" -ForegroundColor Cyan

# VPS connection details
$vpsUser = "root"
$vpsHost = "160.187.68.243"
$vpsPath = "/var/www/ntt"
$identityFile = "C:\Users\HPi9\.ssh\id_ed25519_ntt"

Write-Host "`n[1/6] Uploading fixed .env..." -ForegroundColor Yellow
scp -i $identityFile "d:\NTT_LOCAL_SERVER\.env" "${vpsUser}@${vpsHost}:${vpsPath}/.env"

Write-Host "`n[2/6] Uploading upgraded ApiAuthController.php..." -ForegroundColor Yellow
scp -i $identityFile "d:\NTT_LOCAL_SERVER\app\Http\Controllers\ApiAuthController.php" "${vpsUser}@${vpsHost}:${vpsPath}/app/Http/Controllers/ApiAuthController.php"

Write-Host "`n[3/6] Uploading StaffResource.php (admin buttons)..." -ForegroundColor Yellow
scp -i $identityFile "d:\NTT_LOCAL_SERVER\app\Filament\Resources\StaffResource.php" "${vpsUser}@${vpsHost}:${vpsPath}/app/Filament/Resources/StaffResource.php"

Write-Host "`n[4/6] Uploading updated api.php routes..." -ForegroundColor Yellow
scp -i $identityFile "d:\NTT_LOCAL_SERVER\routes\api.php" "${vpsUser}@${vpsHost}:${vpsPath}/routes/api.php"

Write-Host "`n[5/6] Uploading email_verifications migration..." -ForegroundColor Yellow
scp -i $identityFile "d:\NTT_LOCAL_SERVER\database\migrations\2026_04_15_100000_create_email_verifications_table.php" "${vpsUser}@${vpsHost}:${vpsPath}/database/migrations/"

Write-Host "`n[6/6] Running migration & clearing caches on VPS..." -ForegroundColor Yellow
ssh -i $identityFile ${vpsUser}@${vpsHost} "cd ${vpsPath} && php artisan migrate --force && php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear && echo 'ALL DONE'"

Write-Host "`n=== Deployment Complete ===" -ForegroundColor Green
Write-Host "Admin panel at: https://backend.newsthetruth.com/admin/staff" -ForegroundColor Cyan
Write-Host "You should now see 'Verify Mail' and 'Send Auth' buttons on each staff row." -ForegroundColor Cyan

Write-Host "`n=== Deployment Complete ===" -ForegroundColor Green
Write-Host "Admin panel at: https://backend.newsthetruth.com/admin/staff" -ForegroundColor Cyan
Write-Host "You should now see 'Verify Mail' and 'Send Auth' buttons on each staff row." -ForegroundColor Cyan
