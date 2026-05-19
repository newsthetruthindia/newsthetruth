<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

$email = 'newsthetruthindia@gmail.com';
$password = 'Admin@123!'; // Temporary password for the user

echo "--- Restoring Admin User: {$email} ---\n";

$user = User::withTrashed()->where('email', $email)->first();

if ($user) {
    echo "User found in database (maybe soft deleted or inactive). Restoring...\n";
    if ($user->trashed()) {
        $user->restore();
    }
    $user->password = Hash::make($password);
    $user->type = 'admin';
    $user->save();
    echo "User updated and restored.\n";
} else {
    echo "Creating new admin user...\n";
    $user = User::create([
        'firstname' => 'Admin',
        'lastname' => 'NTT',
        'email' => $email,
        'password' => Hash::make($password),
        'type' => 'admin',
    ]);
    echo "User created successfully.\n";
}

// Ensure Admin role is assigned (Spatie)
$adminRole = Role::where('name', 'Admin')->first();
if ($adminRole) {
    if (!$user->hasRole('Admin')) {
        $user->assignRole('Admin');
        echo "Role 'Admin' assigned.\n";
    } else {
        echo "User already has 'Admin' role.\n";
    }
} else {
    echo "Role 'Admin' not found in Spatie tables. Generating role...\n";
    Role::create(['name' => 'Admin']);
    $user->assignRole('Admin');
    echo "'Admin' role created and assigned.\n";
}

echo "\n--- SMTP Configuration Update (Next Step) ---\n";
echo "Done with user restoration. Please update .env to fix mail delivery.\n";
