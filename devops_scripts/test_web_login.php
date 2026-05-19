<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'dasdipaneeta97@gmail.com';
$password = 'ntt@password2026';

if (Illuminate\Support\Facades\Auth::guard('web')->attempt(['email' => $email, 'password' => $password])) {
    echo "WEB_LOGIN_SUCCESS\n";
    $user = Illuminate\Support\Facades\Auth::guard('web')->user();
    echo "CAN_ACCESS_PANEL: " . ($user->canAccessPanel(Filament\Facades\Filament::getCurrentPanel()) ? 'YES' : 'NO') . "\n";
} else {
    echo "WEB_LOGIN_FAILED\n";
}
