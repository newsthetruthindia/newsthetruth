@echo off
title NTT Media Upload Resume
color 0E

echo ===================================================
echo     NTT MEDIA UPLOAD RESUME
echo ===================================================
echo Resuming transfer of /public/uploads (~9GB remaining)
echo.

date /t
time /t

scp -r -o StrictHostKeyChecking=no -i C:\Users\HPi9\.ssh\id_ed25519_ntt d:\NTT_LOCAL_SERVER\public\uploads root@117.252.16.132:/var/www/ntt/public/

echo.
echo ===================================================
echo     UPLOAD COMPLETED!
echo ===================================================
date /t
time /t

echo You can now close this window safely.
pause
