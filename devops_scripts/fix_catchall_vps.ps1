$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"

# Use sed to replace the catch-all route regex on the VPS
$fixCommands = @"
echo '--- Checking current catch-all route ---'
grep -n "public.page" /var/www/ntt/routes/web.php

echo '--- Fixing catch-all route to exclude /admin ---'
sed -i "s/->where('x', '.*')/->where('x', '^(?!admin).*')/" /var/www/ntt/routes/web.php

echo '--- Also fixing via PHP sed (double quotes version) ---'
sed -i 's/->where(.x., ..*..)/->where("x", "^(?!admin).*")/' /var/www/ntt/routes/web.php

echo '--- Verifying fix ---'
tail -5 /var/www/ntt/routes/web.php

echo '--- Clearing route cache ---'
cd /var/www/ntt
php artisan route:clear
php artisan config:clear
php artisan route:cache

echo '--- Checking admin route exists ---'
php artisan route:list 2>&1 | grep "admin/dashboard" | head -3

echo '--- Done ---'
"@

Write-Host "=== Fixing catch-all route on VPS ===" -ForegroundColor Green
$fixCommands | ssh -i $sshKey -o StrictHostKeyChecking=no "$vpsUser@$vpsHost" bash
