$php = "C:\Users\HPi9\AppData\Local\Programs\Local\resources\extraResources\lightning-services\php-8.2.27+1\bin\win64\php.exe"
$composer = "C:\Users\HPi9\AppData\Local\Programs\Local\resources\extraResources\bin\composer\composer.phar"
$backend = "d:\NTT_LOCAL_SERVER"

Write-Host "=== Running dump-autoload ===" -ForegroundColor Green
& $php $composer dump-autoload --working-dir=$backend 2>&1

Write-Host "=== Checking autoload.php ===" -ForegroundColor Green
Test-Path "$backend\vendor\autoload.php"
