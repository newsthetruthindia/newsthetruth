$baseDir = "C:\Users\HPi9\AppData\Local\Programs\Local\resources\extraResources\lightning-services\php-8.2.27+1\bin\win64"
$iniFile = "$baseDir\php.ini"
$templateFile = "$baseDir\php.ini-development"

if (-not (Test-Path $iniFile)) {
    Write-Host "Creating php.ini from template..."
    Copy-Item $templateFile $iniFile
}

Write-Host "Enabling extensions..."
$content = Get-Content $iniFile

# Uncomment extensions
$content = $content -replace ";extension=curl", "extension=curl"
$content = $content -replace ";extension=fileinfo", "extension=fileinfo"
$content = $content -replace ";extension=gd", "extension=gd"
$content = $content -replace ";extension=intl", "extension=intl"
$content = $content -replace ";extension=mbstring", "extension=mbstring"
$content = $content -replace ";extension=openssl", "extension=openssl"
$content = $content -replace ";extension=pdo_mysql", "extension=pdo_mysql"
$content = $content -replace ";extension=zip", "extension=zip"

# Set extension_dir
$content = $content -replace ";extension_dir = ""ext""", "extension_dir = ""ext"""

Set-Content $iniFile $content
Write-Host "php.ini updated."
