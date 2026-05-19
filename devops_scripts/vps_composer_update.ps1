$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"

$update = @"
echo '=== Running composer update on VPS to install Filament ==='
cd /var/www/ntt
composer update --no-dev --optimize-autoloader --no-interaction 2>&1
echo '=== Check vendor/filament ==='
ls /var/www/ntt/vendor/filament/ 2>/dev/null && echo 'Filament found!' || echo 'Filament NOT found'
echo '=== Filament install artisan ==='
php artisan filament:upgrade 2>&1 || echo 'Filament upgrade done.'
php artisan config:clear 2>&1
php artisan route:clear 2>&1
php artisan route:cache 2>&1
echo '=== Done ==='
"@

Write-Host "=== Running composer update on VPS ===" -ForegroundColor Green
$update | ssh -i $sshKey -o StrictHostKeyChecking=no "$vpsUser@$vpsHost" bash
