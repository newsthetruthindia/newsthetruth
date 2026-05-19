@echo off
echo y | d:\NTT_WEBSITE\tools\plink.exe -ssh root@117.252.16.132 -pw "$9T%%Lk057bzu" exit 2>nul
type d:\NTT_WEBSITE\ntt_nginx.conf | d:\NTT_WEBSITE\tools\plink.exe -batch -ssh root@117.252.16.132 -pw "$9T%%Lk057bzu" "cat > /etc/nginx/conf.d/ntt.conf"
d:\NTT_WEBSITE\tools\plink.exe -batch -ssh root@117.252.16.132 -pw "$9T%%Lk057bzu" "nginx -t && systemctl restart nginx && chown -R nginx:nginx /var/www/ntt && chmod -R 755 /var/www/ntt/public"
echo Done.
