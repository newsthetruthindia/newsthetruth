@echo off
title NTT VPS Migration
color 0B

echo ===================================================
echo     NTT FULL VPS MIGRATION: BACKGROUND TRANSFER
echo ===================================================
echo Starting migration process... 
echo.

date /t
time /t

echo.
echo [1/3] Transferring Database Backup (147MB)...
scp -o StrictHostKeyChecking=no -i C:\Users\HPi9\.ssh\id_ed25519_ntt d:\NTT_WEBSITE\backups\database_backup.sql root@117.252.16.132:/tmp/database_backup.sql

echo.
echo [2/3] Transferring Codebase Archive (10.4GB) - This will take a while...
scp -o StrictHostKeyChecking=no -i C:\Users\HPi9\.ssh\id_ed25519_ntt d:\NTT_WEBSITE\ntt_codebase.tar.gz root@117.252.16.132:/tmp/ntt_codebase.tar.gz

echo.
echo Pre-creating remote directory for media...
ssh -o StrictHostKeyChecking=no -i C:\Users\HPi9\.ssh\id_ed25519_ntt root@117.252.16.132 "mkdir -p /var/www/ntt/public"

echo.
echo [3/3] Transferring Media Assets (~16.8GB) - This will take several hours...
scp -r -o StrictHostKeyChecking=no -i C:\Users\HPi9\.ssh\id_ed25519_ntt d:\NTT_LOCAL_SERVER\public\uploads root@117.252.16.132:/var/www/ntt/public/

echo.
echo ===================================================
echo     MIGRATION COMPLETED!
echo ===================================================
date /t
time /t

echo You can now close this window safely.
pause
