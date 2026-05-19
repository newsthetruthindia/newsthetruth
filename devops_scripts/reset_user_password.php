<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'dasdipaneeta97@gmail.com';
$newPassword = 'ntt@password2026';
$user = App\Models\User::where('email', $email)->first();

if (!$user) {
    echo "USER_NOT_FOUND: $email\n";
    exit;
}

$user->password = Illuminate\Support\Facades\Hash::make($newPassword);
$user->email_verified = 1;
$user->email_verified_at = now();
$user->save();

echo "SUCCESS: Password reset for $email\n";
echo "NEW_PASSWORD: $newPassword\n";
