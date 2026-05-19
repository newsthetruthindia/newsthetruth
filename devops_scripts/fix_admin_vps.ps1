$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"
$vpsPath = "/var/www/ntt"

$commands = @"
set -e

echo '--- Step 1: Check bootstrap/providers.php ---'
cat $vpsPath/bootstrap/providers.php 2>/dev/null || echo 'FILE NOT FOUND'

echo '--- Step 2: Write correct providers.php ---'
cat > $vpsPath/bootstrap/providers.php << 'PHPEOF'
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
];
PHPEOF

echo '--- Step 3: Clear all caches ---'
cd $vpsPath
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

echo '--- Step 4: Check Filament routes ---'
php artisan route:list 2>&1 | grep -i 'admin' | head -20

echo '--- Step 5: Rebuild config cache ---'
php artisan config:cache
php artisan route:cache

echo '--- Done ---'
"@

Write-Host "=== Connecting to VPS and fixing AdminPanelProvider ===" -ForegroundColor Green
$commands | ssh -i $sshKey -o StrictHostKeyChecking=no "$vpsUser@$vpsHost" bash
