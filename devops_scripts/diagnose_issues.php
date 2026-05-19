<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "--- 1. SEARCHING FOR USER: Dipaneeta Das ---\n";
$user = App\Models\User::where('firstname', 'LIKE', '%Dipaneeta%')
    ->orWhere('email', 'LIKE', '%dipaneeta%')
    ->first();

if ($user) {
    echo "ID: " . $user->id . "\n";
    echo "NAME: " . $user->firstname . " " . $user->lastname . "\n";
    echo "EMAIL: " . $user->email . "\n";
    echo "TYPE: " . $user->type . "\n";
    echo "VERIFIED: " . ($user->email_verified ? 'YES' : 'NO') . "\n";
} else {
    echo "USER NOT FOUND.\n";
}

echo "\n--- 2. TESTING EMAIL: newsthetruthindia@gmail.com ---\n";
try {
    $to = "newsthetruthindia@gmail.com";
    $subject = "NTT System Diagnostic Test";
    $body = "This is a diagnostic email from the backend to verify SMTP health.";
    
    // Using Laravel Mail facade
    \Illuminate\Support\Facades\Mail::raw($body, function ($message) use ($to, $subject) {
        $message->to($to)->subject($subject);
    });
    
    echo "EMAIL STATUS: SUCCESS - DISPATCHED\n";
} catch (\Exception $e) {
    echo "EMAIL STATUS: FAILED\n";
    echo "ERROR: " . $e->getMessage() . "\n";
}
