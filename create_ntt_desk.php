<?php

// NTT Desk Creation Script
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\UserDetail;
use App\Models\Tag;
use Illuminate\Support\Facades\Hash;

try {
    // 1. Create User
    $user = User::firstOrCreate(
        ['email' => 'desk@newsthetruth.com'],
        [
            'firstname' => 'NTT',
            'lastname' => 'Desk',
            'password' => Hash::make('ntt_desk_root_2026'),
            'email_verified_at' => now(),
        ]
    );
    
    // 2. Assign Role (using Spatie)
    if (!$user->hasRole('Reporter')) {
        $user->assignRole('Reporter');
        echo "Role 'Reporter' assigned to NTT Desk.\n";
    }

    // 3. User Details
    UserDetail::firstOrCreate(
        ['user_id' => $user->id],
        [
            'designation' => 'Official Editorial Desk',
            'bio' => 'The central editorial desk for News The Truth, coordinating breaking news and official statements.'
        ]
    );

    // 4. Tag
    Tag::firstOrCreate(
        ['slug' => 'ntt-desk'],
        [
            'title' => 'NTT Desk',
            'description' => 'Official news and bulletins directly from the NTT Editorial Desk.',
            'user_id' => $user->id // Required field in DB
        ]
    );

    echo "NTT Desk User and Tag created/verified successfully.\n";
    echo "User ID: " . $user->id . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
