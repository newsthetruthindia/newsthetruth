#!/bin/bash
mv /tmp/sync_utf8.txt /tmp/range_sync.txt 2>/dev/null
php /var/www/ntt/import_range.php
cd /var/www/ntt
while read -r line; do
    clean_line=$(echo "$line" | tr -d '\r')
    if [ -n "$clean_line" ]; then
        url="https://newsthetruth.com/$clean_line"
        relative_path=$(echo "$clean_line" | sed 's|^/||')
        dir=$(dirname "$relative_path")
        mkdir -p "public/$dir"
        if [ ! -f "public/$relative_path" ]; then
            echo "Downloading: $relative_path"
            curl -s -L -o "public/$relative_path" "$url"
        fi
    fi
done < /tmp/image_list.txt
