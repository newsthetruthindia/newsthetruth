$php = "C:\Users\HPi9\AppData\Local\Programs\Local\resources\extraResources\lightning-services\php-8.2.27+1\bin\win64\php.exe"
$composer = "C:\Users\HPi9\AppData\Local\Programs\Local\resources\extraResources\bin\composer\composer.phar"
$backend = "d:\NTT_LOCAL_SERVER"

Write-Host "Starting Composer Update in $backend..."
& $php $composer update --working-dir=$backend --with-all-dependencies
