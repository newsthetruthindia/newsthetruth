<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "--- ADMINS AUDIT ---\n";
$admins = App\Models\User::where('type', 'admin')->get(['id', 'firstname', 'email', 'google2fa_secret', 'email_verified']);

foreach ($admins as $u) {
    echo "ID: {$u->id} | Name: {$u->firstname} | Email: {$u->email} | 2FA: " . ($u->google2fa_secret ? 'ENABLED' : 'OFF') . " | Verified: " . ($u->email_verified ? 'YES' : 'NO') . "\n";
}
