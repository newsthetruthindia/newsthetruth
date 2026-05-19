#!/bin/bash
wget https://getcomposer.org/installer -O composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
cd /var/www/ntt
composer install --no-dev --optimize-autoloader
chown -R nginx:nginx vendor
chown -R nginx:nginx /var/www/ntt/storage /var/www/ntt/bootstrap/cache
php artisan optimize:clear
php artisan storage:link
echo "Laravel setup completed."
