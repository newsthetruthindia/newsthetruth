<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::find(385);
if ($user) {
    $user->email_verified = 1;
    $user->email_verified_at = now();
    $user->save();
    echo "SUCCESS: Dipaneeta Das is now verified.\n";
} else {
    echo "ERROR: User not found.\n";
}
