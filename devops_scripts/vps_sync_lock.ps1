$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"

Write-Host "=== Uploading updated composer.lock to VPS ===" -ForegroundColor Green
scp -i $sshKey `
    "d:\NTT_LOCAL_SERVER\composer.json" `
    "d:\NTT_LOCAL_SERVER\composer.lock" `
    "${vpsUser}@${vpsHost}:/var/www/ntt/"

Write-Host "=== Running composer install to hydrate vendor from new lock file ===" -ForegroundColor Green
$install = @"
cd /var/www/ntt
composer install --no-dev --optimize-autoloader --no-interaction 2>&1
ls /var/www/ntt/vendor/filament/ 2>/dev/null && echo 'FILAMENT OK' || echo 'FILAMENT MISSING'
php artisan config:clear
php artisan route:clear
php artisan route:cache

echo '=== Migrate Spatie permission tables ==='
php artisan migrate --force 2>&1

echo '=== Publish Filament assets ==='
php artisan vendor:publish --tag=filament-assets --force 2>&1

echo '=== Check admin route ==='
php artisan route:list 2>&1 | grep "admin/login" | head -3
echo '=== Done ==='
"@

$install | ssh -i $sshKey -o StrictHostKeyChecking=no "$vpsUser@$vpsHost" bash
