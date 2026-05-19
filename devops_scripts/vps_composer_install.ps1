$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"

$install = @"
echo '=== PHP Version on VPS ==='
php --version

echo '=== Composer Version ==='
composer --version 2>/dev/null || (curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && echo 'Composer installed')

echo '=== Running composer install ==='
cd /var/www/ntt
composer install --no-dev --optimize-autoloader --no-interaction 2>&1

echo '=== Check vendor/filament ==='
ls /var/www/ntt/vendor/filament/ 2>/dev/null && echo 'Filament found!' || echo 'Filament NOT found'

echo '=== Done ==='
"@

Write-Host "=== Installing Filament on VPS ===" -ForegroundColor Green
$install | ssh -i $sshKey -o StrictHostKeyChecking=no "$vpsUser@$vpsHost" bash
