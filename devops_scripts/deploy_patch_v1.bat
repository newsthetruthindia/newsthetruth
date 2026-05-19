@echo off
ssh -i %USERPROFILE%\.ssh\id_ed25519_ntt -o StrictHostKeyChecking=no root@117.252.16.132 "rm -f /var/www/ntt/patch_vps_v1.php"
scp -i %USERPROFILE%\.ssh\id_ed25519_ntt -o StrictHostKeyChecking=no d:\NTT_WEBSITE\patch_vps_v1.php root@117.252.16.132:/var/www/ntt/patch_vps_v1.php
ssh -i %USERPROFILE%\.ssh\id_ed25519_ntt -o StrictHostKeyChecking=no root@117.252.16.132 "php /var/www/ntt/patch_vps_v1.php"
