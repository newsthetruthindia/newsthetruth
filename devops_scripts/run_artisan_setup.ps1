$php = "C:\Users\HPi9\AppData\Local\Programs\Local\resources\extraResources\lightning-services\php-8.2.27+1\bin\win64\php.exe"
$backend = "d:\NTT_LOCAL_SERVER"

Write-Host "=== Filament: Scaffold Admin Panel ===" -ForegroundColor Green
& $php "$backend\artisan" filament:install --panels --no-interaction 2>&1

Write-Host "=== Publish Filament Assets ===" -ForegroundColor Green
& $php "$backend\artisan" vendor:publish --tag=filament-config --force 2>&1
& $php "$backend\artisan" vendor:publish --tag=filament-panels-config --force 2>&1

Write-Host "=== Publish Spatie Permission ===" -ForegroundColor Green
& $php "$backend\artisan" vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" 2>&1

Write-Host "=== Run Migrations ===" -ForegroundColor Green
& $php "$backend\artisan" migrate --force 2>&1

Write-Host "=== Clear Caches ===" -ForegroundColor Green
& $php "$backend\artisan" config:clear 2>&1
& $php "$backend\artisan" cache:clear 2>&1
& $php "$backend\artisan" view:clear 2>&1
& $php "$backend\artisan" route:clear 2>&1

Write-Host "=== Done ===" -ForegroundColor Green
