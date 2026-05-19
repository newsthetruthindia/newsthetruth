#!/bin/bash
# Add domain name to nginx server_name

echo "=== Current server_name in ntt.conf ==="
grep server_name /etc/nginx/conf.d/ntt.conf

echo "=== Updating server_name to include domain ==="
sed -i "s/server_name 117.252.16.132;/server_name 117.252.16.132 newsthetruth.com www.newsthetruth.com;/" /etc/nginx/conf.d/ntt.conf

echo "=== Verify change ==="
grep server_name /etc/nginx/conf.d/ntt.conf

echo "=== Test nginx config ==="
nginx -t 2>&1

echo "=== Reload nginx ==="
systemctl reload nginx && echo "Nginx reloaded OK" || service nginx reload && echo "Nginx service reloaded OK"

echo "=== Test admin url locally ==="
curl -s -o /dev/null -w "%{http_code}" --header 'Host: newsthetruth.com' http://127.0.0.1/admin

echo ""
echo "=== Done ==="
