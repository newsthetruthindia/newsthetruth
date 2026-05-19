<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'dasdipaneeta97@gmail.com';
$password = 'ntt@password2026';

if (Illuminate\Support\Facades\Auth::attempt(['email' => $email, 'password' => $password])) {
    echo "LOGIN_SUCCESS\n";
} else {
    echo "LOGIN_FAILED\n";
}
