#!/bin/bash
cd /var/www/ntt

echo "=== Fix: Comment out old Intervention Image provider ==="
# Comment it out
php -r "
\$file = 'config/app.php';
\$content = file_get_contents(\$file);
\$content = str_replace(
    \"Intervention\\\\Image\\\\ImageServiceProvider::class\",
    \"// Intervention\\\\Image\\\\ImageServiceProvider::class\",
    \$content
);
file_put_contents(\$file, \$content);
echo 'Done: config/app.php updated' . PHP_EOL;
"

echo "=== Step 2: Run dump-autoload ==="
composer dump-autoload --optimize 2>&1

echo "=== Step 3: Check if Filament is installed ==="
ls vendor/filament/ 2>/dev/null && echo "FILAMENT OK" || echo "FILAMENT MISSING"

echo "=== Step 4: Run Filament upgrade ==="
php artisan filament:upgrade 2>&1 || echo "filament upgrade not available"

echo "=== Step 5: Clear and rebuild all caches ==="
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan route:cache
php artisan config:cache

echo "=== Step 6: Run migrations ==="
php artisan migrate --force 2>&1 | tail -10

echo "=== Step 7: Check /admin is routed by Filament ==="
php artisan route:list 2>&1 | grep "admin/login" | head -5 || echo "No Filament login route found"

echo "=== DONE ==="
