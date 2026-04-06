<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

echo "=== PROVISIONING MISSING REPORTER ACCOUNTS ===\n";

$reporters = [
    ['firstname' => 'Aniket', 'lastname' => 'Datta', 'email' => 'aniket.datta@newsthetruth.com'],
    ['firstname' => 'Sankha Subhra', 'lastname' => 'Das', 'email' => 'sankha.das@newsthetruth.com'],
    ['firstname' => 'Suprav', 'lastname' => 'Banerjee', 'email' => 'suprav.banerjee@newsthetruth.com'],
];

$role = Role::where('name', 'Reporter')->first();

foreach ($reporters as $data) {
    $user = User::where('email', $data['email'])->first();
    if (!$user) {
        $user = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => Hash::make(Str::random(16)),
            'type' => 'employee'
        ]);
        echo "Created user: {$data['firstname']} {$data['lastname']}\n";
    } else {
        echo "User already exists: {$data['firstname']} {$data['lastname']}\n";
    }

    if ($role && !$user->hasRole('Reporter')) {
        $user->assignRole('Reporter');
        echo "  - Assigned Reporter role\n";
    }
}

echo "=== PROVISIONING COMPLETE ===\n";
?>
