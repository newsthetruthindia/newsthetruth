#!/bin/bash
set -e

echo "Enabling PHP 8.2 module..."
dnf module reset php -y
dnf module enable php:8.2 -y

echo "Installing Nginx, MariaDB, PHP and extensions..."
dnf install -y nginx mariadb-server php-fpm php-cli php-mysqlnd php-gd php-xml php-mbstring php-zip php-curl php-intl php-bcmath unzip git curl wget

echo "Starting and enabling services..."
systemctl enable --now nginx mariadb php-fpm

echo "LEMP Stack Installation Complete!"
