mysql -e "CREATE DATABASE IF NOT EXISTS ntt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS 'ntt_user'@'localhost' IDENTIFIED BY 'NttPass2026!';"
mysql -e "GRANT ALL PRIVILEGES ON ntt.* TO 'ntt_user'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
mkdir -p /var/www/ntt/public
chown -R nginx:nginx /var/www/ntt
systemctl restart nginx
echo "DB and Nginx setup finalized."
