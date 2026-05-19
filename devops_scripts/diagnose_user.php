<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'dasdipaneeta97@gmail.com';
$user = App\Models\User::where('email', $email)->first();

if (!$user) {
    echo "USER_NOT_FOUND: $email\n";
    exit;
}

echo "USER_FOUND: " . $user->id . "\n";
echo "NAME: " . $user->firstname . " " . $user->lastname . "\n";
echo "EMAIL_VERIFIED: " . ($user->email_verified_at ? 'YES' : 'NO') . "\n";
echo "EMAIL_VERIFIED_FIELD: " . ($user->email_verified ? '1' : '0') . "\n";
echo "TWO_FACTOR_ENABLED: " . ($user->two_factor_secret ? 'YES' : 'NO') . "\n";
echo "STATUS: " . ($user->status ?? 'N/A') . "\n";
echo "ROLES: " . $user->roles->pluck('name')->implode(', ') . "\n";
echo "PASSWORD_HASH_START: " . substr($user->password, 0, 10) . "...\n";
echo "PASSWORD_HASH_LENGTH: " . strlen($user->password) . "\n";
