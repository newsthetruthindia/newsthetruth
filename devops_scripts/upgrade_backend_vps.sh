#!/bin/bash
# NTT Backend Upgrade Script - Run on VPS after uploading
# Usage: bash upgrade_backend.sh

VPS_PATH="/var/www/ntt"
PHP_BIN="php"

echo "=== [1/5] Pulling latest backend from GitHub ==="
cd $VPS_PATH
git pull origin main

echo "=== [2/5] Installing updated Composer dependencies ==="
$PHP_BIN -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
$PHP_BIN composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
composer install --no-dev --optimize-autoloader

echo "=== [3/5] Running Filament Upgrade ==="
$PHP_BIN artisan filament:upgrade
$PHP_BIN artisan filament:optimize

echo "=== [4/5] Running Migrations ==="
# This adds Spatie permission tables
$PHP_BIN artisan migrate --force

echo "=== [5/5] Optimizing Application ==="
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
$PHP_BIN artisan storage:link

# 6. Final Permissions Enforcement (CRITICAL: MUST BE apache:apache)
echo "Enforcing permissions protocol..."
chown -R apache:apache $VPS_PATH/storage $VPS_PATH/bootstrap/cache $VPS_PATH/public/js/filament $VPS_PATH/public/css/filament 2>/dev/null

echo ""
echo "=== Deployment Complete! ==="
echo "Admin Panel URL: https://newsthetruth.com/admin"
echo "Login with your existing account credentials."
echo ""
