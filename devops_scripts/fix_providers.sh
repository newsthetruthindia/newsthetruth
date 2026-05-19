#!/bin/bash
# Fix: Register Filament AdminPanelProvider in config/app.php (Laravel 9 style)
cd /var/www/ntt

echo "=== Current providers in config/app.php ==="
grep -n "AdminPanelProvider\|RouteServiceProvider" config/app.php

echo "=== Adding AdminPanelProvider if not present ==="
if ! grep -q "AdminPanelProvider" config/app.php; then
    # Add AdminPanelProvider after RouteServiceProvider
    sed -i "s/App\\\\Providers\\\\RouteServiceProvider::class,/App\\\\Providers\\\\RouteServiceProvider::class,\n        App\\\\Providers\\\\Filament\\\\AdminPanelProvider::class,/" config/app.php
    echo "Added AdminPanelProvider to config/app.php"
else
    echo "AdminPanelProvider already in config/app.php"
fi

echo "=== Verify ==="
grep -n "AdminPanelProvider" config/app.php

echo "=== Clear all caches ==="
php artisan config:clear
php artisan route:clear
php artisan cache:clear

echo "=== Rebuild ==="
php artisan config:cache
php artisan route:cache

echo "=== Check if Filament admin routes appear ==="
php artisan route:list 2>&1 | grep filament | head -10

echo "=== Done ==="
