#!/bin/bash
echo "Setting up database..."
mysql -e "CREATE DATABASE IF NOT EXISTS newstew1_main CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS 'newstew1_newsthet'@'localhost' IDENTIFIED BY '3RdX?tPig*^$';"
mysql -e "GRANT ALL PRIVILEGES ON newstew1_main.* TO 'newstew1_newsthet'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
echo "Importing database..."
mysql newstew1_main < /tmp/database_backup.sql
echo "Database restoration complete."
