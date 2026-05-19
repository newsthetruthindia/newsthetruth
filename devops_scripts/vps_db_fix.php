<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Post;

echo "=== STARTING DATABASE CLEANUP & ROLE SETUP ===\n";

// 1. Create Roles
$reporterRole = Role::firstOrCreate(['name' => 'Reporter', 'guard_name' => 'web']);
$cjRole = Role::firstOrCreate(['name' => 'Citizen Journalist', 'guard_name' => 'web']);
echo "Roles 'Reporter' and 'Citizen Journalist' ensured.\n";

// 2. Assign Reporter Role to Staff
$staffIds = [1, 2, 7, 9, 385]; // Sourav, Rony, Tamal, Titas, Dipaneeta
foreach ($staffIds as $id) {
    $user = User::find($id);
    if ($user) {
        $user->assignRole('Reporter');
        echo "Assigned Reporter role to {$user->firstname} (ID: $id)\n";
    }
}

// 3. Consolidate "NTT Desk" (Master ID 0)
// Standardize variations to user_id 0 and 'NTT Desk'
$deskVariations = ['Ntt Desk', 'desk', 'NTT Staff', 'Staff Reporter', 'me', '4869', 'Election In Rajasthan Is', 'Poll In West', 'Live Update', 'Election In Rajasthan Is', 'Poll In West', 'Poll Saw A Notable Performance', 'Poll Seat That Underwent', 'Poll Elections For Seven', 'Poll In West'];
$affected = Post::whereIn('reporter_name', $deskVariations)
    ->orWhereIn('user_id', [8, 23, 24, 192]) // Ghost IDs typically associated with Desk or placeholders
    ->update(['user_id' => 0, 'reporter_name' => 'NTT Desk']);
echo "Standardized $affected posts to 'NTT Desk'.\n";

// 4. Resolve Master Reporter IDs
$reporters = [
    'Tamal Saha' => 7,
    'Titas Mukherjee' => 9,
    'Rony Santra' => 2,
    'Dipaneeta Das' => 385,
    'SOURAV ADKARI' => 1,
    'Aniket Datta' => 9, // Assigning to Titas/System as he doesn't have an ID
];

foreach ($reporters as $name => $id) {
    $affected = Post::where('reporter_name', $name)->update(['user_id' => $id]);
    echo "Updated $affected posts for reporter '$name' to user_id $id.\n";
}

// 5. Special Case: Ankit Salvi (No ID, but 313 articles)
// Keeping name but setting user_id to 0 for system background
$affected = Post::where('reporter_name', 'Ankit Salvi')->update(['user_id' => 0]);
echo "Standardized $affected 'Ankit Salvi' posts to user_id 0.\n";

echo "=== CLEANUP COMPLETED ===\n";
