$php = "C:\Users\HPi9\AppData\Local\Programs\Local\resources\extraResources\lightning-services\php-8.2.27+1\bin\win64\php.exe"
$backend = "d:\NTT_LOCAL_SERVER"

Write-Host "=== Running Filament Install ===" -ForegroundColor Green
& $php "$backend\artisan" filament:install --panels --quiet --no-interaction 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "Filament install had issues, trying to publish assets only..." -ForegroundColor Yellow
}

Write-Host "=== Publishing Filament Config ===" -ForegroundColor Green
& $php "$backend\artisan" vendor:publish --tag=filament-config --force 2>&1

Write-Host "=== Publishing Spatie Permission ===" -ForegroundColor Green
& $php "$backend\artisan" vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --force 2>&1

Write-Host "=== Clearing Caches ===" -ForegroundColor Green
& $php "$backend\artisan" config:clear 2>&1
& $php "$backend\artisan" cache:clear 2>&1
& $php "$backend\artisan" view:clear 2>&1

Write-Host "=== Done ===" -ForegroundColor Green
