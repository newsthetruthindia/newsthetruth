<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting Cleanup...\n";

// 1. Unverified users older than 7 days
$unverifiedCount = User::where('type', 'user')
    ->whereNull('email_verified_at')
    ->where('created_at', '<', now()->subDays(7))
    ->count();

User::where('type', 'user')
    ->whereNull('email_verified_at')
    ->where('created_at', '<', now()->subDays(7))
    ->delete();

echo "Removed {$unverifiedCount} unverified inactive users.\n";

// 2. Possible fake accounts
$fakeCount = User::where('type', 'user')
    ->where(function ($q) {
        $q->where('email', 'like', '%test%')
          ->orWhere('email', 'like', '%asdf%')
          ->orWhere('email', 'like', '%tempmail%')
          ->orWhere('email', 'like', '%teleworm%')
          ->orWhere('email', 'like', '%sharklasers%')
          ->orWhereNull('lastname');
    })
    ->count();

User::where('type', 'user')
    ->where(function ($q) {
        $q->where('email', 'like', '%test%')
          ->orWhere('email', 'like', '%asdf%')
          ->orWhere('email', 'like', '%tempmail%')
          ->orWhere('email', 'like', '%teleworm%')
          ->orWhere('email', 'like', '%sharklasers%')
          ->orWhereNull('lastname');
    })
    ->delete();

echo "Removed {$fakeCount} potentially fake accounts.\n";

echo "Cleanup Complete.\n";
