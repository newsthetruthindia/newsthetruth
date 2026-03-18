<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'newsthetruthindia@gmail.com')->first();
if ($user) {
    if (!$user->email_verified_at) {
        $user->email_verified_at = now();
        $user->save();
        echo "User verified successfully.\n";
    } else {
        echo "User was already verified.\n";
    }
} else {
    echo "User not found.\n";
}
