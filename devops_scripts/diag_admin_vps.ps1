$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"

$diag = @"
echo '=== Last 10 lines of routes/web.php ==='
tail -10 /var/www/ntt/routes/web.php

echo ''
echo '=== Checking handleRoute for admin exclusion ==='
php /var/www/ntt/artisan route:clear
php /var/www/ntt/artisan route:list 2>&1 | grep -E 'admin.*filament|filament.*admin' | head -5

echo ''
echo '=== Filament routes check ==='
php /var/www/ntt/artisan route:list 2>&1 | grep 'admin/login\|admin.*filament\|livewire' | head -10

echo ''
echo '=== Bootstrap providers ==='
cat /var/www/ntt/bootstrap/providers.php

echo ''
echo '=== Check if vendor/filament exists ==='
ls /var/www/ntt/vendor/filament/ | head -5

echo ''
echo '=== Clear caches and check admin login route ==='
cd /var/www/ntt
php artisan route:clear
php artisan route:list 2>&1 | grep 'admin/login' | head -5

echo ''
echo '=== Rebuild cache ==='
php artisan route:cache

echo ''
echo '=== Done ==='
"@

Write-Host "=== Running Diagnostic ===" -ForegroundColor Cyan
$diag | ssh -i $sshKey -o StrictHostKeyChecking=no "$vpsUser@$vpsHost" bash
