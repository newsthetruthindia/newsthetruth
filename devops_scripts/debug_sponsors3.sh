cd /var/www/ntt && sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env && php artisan config:clear && curl -s http://localhost/admin/sponsors 2>&1 | head -50
