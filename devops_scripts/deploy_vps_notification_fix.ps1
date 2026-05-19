# NTT Notification Timeout Fix Deployment (Cron Version)
# Run this from d:\NTT_LOCAL_SERVER or d:\NTT_WEBSITE

Write-Host "=== NTT Notification Timeout Fix Deployment ===" -ForegroundColor Cyan

# VPS connection details
$vpsUser = "root"
$vpsHost = "117.252.16.132"
$vpsPath = "/var/www/ntt"
$identityFile = "C:\Users\HPi9\.ssh\id_ed25519_ntt"

Write-Host "`n[0/6] Creating necessary directories on VPS..." -ForegroundColor Yellow
ssh -i $identityFile ${vpsUser}@${vpsHost} "mkdir -p ${vpsPath}/app/Models ${vpsPath}/app/Jobs ${vpsPath}/database/migrations"

Write-Host "`n[1/4] Uploading updated .env (QUEUE_CONNECTION=database)..." -ForegroundColor Yellow
scp -i $identityFile "d:\NTT_LOCAL_SERVER\.env" "${vpsUser}@${vpsHost}:${vpsPath}/.env"

Write-Host "`n[2/6] Uploading new files (Model, Job, Migration)..." -ForegroundColor Yellow
scp -i $identityFile "d:\NTT_LOCAL_SERVER\app\Models\BroadcastLog.php" "${vpsUser}@${vpsHost}:${vpsPath}/app/Models/BroadcastLog.php"
scp -i $identityFile "d:\NTT_LOCAL_SERVER\app\Jobs\BroadcastYoutubeJob.php" "${vpsUser}@${vpsHost}:${vpsPath}/app/Jobs/BroadcastYoutubeJob.php"
scp -i $identityFile "d:\NTT_LOCAL_SERVER\app\Filament\Resources\SubscriberResource.php" "${vpsUser}@${vpsHost}:${vpsPath}/app/Filament/Resources/SubscriberResource.php"
scp -i $identityFile "d:\NTT_LOCAL_SERVER\app\Http\Controllers\Api\SubscriberController.php" "${vpsUser}@${vpsHost}:${vpsPath}/app/Http/Controllers/Api/SubscriberController.php"
scp -i $identityFile "d:\NTT_LOCAL_SERVER\app\Filament\Pages\ManualSocialPoster.php" "${vpsUser}@${vpsHost}:${vpsPath}/app/Filament/Pages/ManualSocialPoster.php"
scp -i $identityFile "d:\NTT_LOCAL_SERVER\database\migrations\2026_04_26_100000_create_broadcast_logs_table.php" "${vpsUser}@${vpsHost}:${vpsPath}/database/migrations/"

Write-Host "`n[3/6] Uploading updated ManageNotifications.php..." -ForegroundColor Yellow
scp -i $identityFile "d:\NTT_LOCAL_SERVER\app\Filament\Pages\ManageNotifications.php" "${vpsUser}@${vpsHost}:${vpsPath}/app/Filament/Pages/ManageNotifications.php"

Write-Host "`n[4/6] Clearing caches on VPS..." -ForegroundColor Yellow
ssh -i $identityFile ${vpsUser}@${vpsHost} "cd ${vpsPath} && php artisan config:clear && php artisan cache:clear && echo 'Cache Clear Complete'"

Write-Host "`n[5/6] Running migrations..." -ForegroundColor Yellow
ssh -i $identityFile ${vpsUser}@${vpsHost} "cd ${vpsPath} && php artisan migrate --force"

Write-Host "`n[6/6] Setting up Cron Job for Queue Worker..." -ForegroundColor Yellow
# This adds the cron job if it doesn't exist
$cronJob = "* * * * * cd $vpsPath && php artisan queue:work --stop-when-empty >> /dev/null 2>&1"
ssh -i $identityFile ${vpsUser}@${vpsHost} "(crontab -l 2>/dev/null | grep -v 'queue:work'; echo '$cronJob') | crontab -"

Write-Host "`n=== Deployment Complete ===" -ForegroundColor Green
Write-Host "The notification system is now using a background queue via Cron." -ForegroundColor Cyan
Write-Host "The 504 Gateway Timeout should no longer occur." -ForegroundColor Cyan
Write-Host "Emails will be sent automatically every minute." -ForegroundColor Cyan
