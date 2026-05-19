<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Targeting Dipaneeta Das (ID 385)
$user = App\Models\User::find(385);
if ($user) {
    // 1. Clear 2FA Block
    $user->google2fa_secret = null;
    
    // 2. Ensure Email is verified
    $user->email_verified = 1;
    $user->email_verified_at = now();
    
    // 3. Reset Password to a stable temporary one
    $user->password = Hash::make('NTTPass@2026');
    
    $user->save();
    
    echo "SUCCESS: Dipaneeta Das (ID 385) has been unlocked.\n";
    echo "2FA: DISABLED\n";
    echo "PASSWORD RESET: 'NTTPass@2026'\n";
} else {
    echo "ERROR: User id 385 not found.\n";
}
