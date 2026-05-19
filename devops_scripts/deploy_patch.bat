@echo off
setlocal enabledelayedexpansion
ssh -i %USERPROFILE%\.ssh\id_ed25519_ntt -o StrictHostKeyChecking=no root@117.252.16.132 "rm -f /var/www/ntt/patch_vps.php"
scp -i %USERPROFILE%\.ssh\id_ed25519_ntt -o StrictHostKeyChecking=no d:\NTT_WEBSITE\patch_vps.php root@117.252.16.132:/var/www/ntt/patch_vps.php
ssh -i %USERPROFILE%\.ssh\id_ed25519_ntt -o StrictHostKeyChecking=no root@117.252.16.132 "php /var/www/ntt/patch_vps.php"
