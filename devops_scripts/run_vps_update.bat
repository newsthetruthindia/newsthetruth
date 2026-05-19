@echo off
echo Connecting to NTT VPS (117.252.16.132) to run the backend upgrade script...
echo.

:: Assuming 'root' is the user, otherwise change to your SSH username like 'ubuntu' or 'hpi9'
ssh root@117.252.16.132 "cd /var/www/ntt && bash upgrade_backend_vps.sh"

echo.
echo If you saw success messages, your VPS Backend is now live!
pause
