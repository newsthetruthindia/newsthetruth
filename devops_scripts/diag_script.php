<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

$email = 'newsthetruthindia@gmail.com';
$user = User::where('email', $email)->first();

echo "--- Admin Analysis ---\n";
if ($user) {
    echo "User found: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Status: " . ($user->status ?? 'N/A') . "\n";
    // Check for Filament access
    try {
        $canAccess = $user->canAccessPanel(\Filament\Facades\Filament::getPanel('admin'));
        echo "Can access admin panel: " . ($canAccess ? 'YES' : 'NO') . "\n";
    } catch (\Exception $e) {
        echo "Error checking panel access: " . $e->getMessage() . "\n";
    }
} else {
    echo "User NOT FOUND in database!\n";
}

echo "\n--- SMTP Testing ---\n";
try {
    Mail::raw('Test email from NTT Backend diagnostic', function ($message) use ($email) {
        $message->to($email)->subject('NTT Diagnostic Test Mail');
    });
    echo "Mail command executed successfully. Check if received.\n";
} catch (\Exception $e) {
    echo "Mail sending FAILED!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
