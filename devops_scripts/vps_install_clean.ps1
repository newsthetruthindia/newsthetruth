$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$h = "root@117.252.16.132"
$opts = @("-i", $sshKey, "-o", "StrictHostKeyChecking=no")

function R($cmd) {
    Write-Host "> $cmd" -ForegroundColor DarkGray
    $result = ssh @opts $h $cmd
    Write-Host $result
    return $result
}

Write-Host "=== Step 1: Install Filament via composer on VPS ===" -ForegroundColor Green
R("cd /var/www/ntt && composer install --no-dev --optimize-autoloader --no-interaction 2>&1 | tail -20")

Write-Host "=== Step 2: Check Filament vendor ===" -ForegroundColor Green
R("ls /var/www/ntt/vendor/filament/ 2>&1 | head -5 || echo FILAMENT_NOT_FOUND")

Write-Host "=== Step 3: Clear caches ===" -ForegroundColor Green
R("cd /var/www/ntt && php artisan config:clear && php artisan route:clear && php artisan route:cache")

Write-Host "=== Step 4: Check admin login route ===" -ForegroundColor Green
R("cd /var/www/ntt && php artisan route:list 2>&1 | grep 'admin/login' || echo 'no admin login route'")

Write-Host "=== Done ===" -ForegroundColor Green
