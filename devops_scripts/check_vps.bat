@echo off
echo y | d:\NTT_WEBSITE\tools\plink.exe -ssh root@117.252.16.132 -pw "$9T%%Lk057bzu" exit 2>nul
d:\NTT_WEBSITE\tools\plink.exe -ssh root@117.252.16.132 -pw "$9T%%Lk057bzu" "echo VPS_CHECK_START; ls -l /tmp/ntt_codebase.tar.gz; ls -l /tmp/database_backup.sql; du -sh /var/www/ntt/public/uploads; echo VPS_CHECK_END"
