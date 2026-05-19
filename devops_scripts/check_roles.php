<?php
// check_roles.php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Role;
use App\Models\User;

echo "=== ROLE SYSTEM CHECK ===\n";

try {
    $roles = Role::all()->pluck('name')->toArray();
    echo "Existing Roles: " . implode(', ', $roles) . "\n";
    
    if (in_array('Reporter', $roles)) {
        echo "[SUCCESS] 'Reporter' role exists.\n";
        $count = User::role('Reporter')->count();
        echo "Users with 'Reporter' role: {$count}\n";
    } else {
        echo "[ERROR] 'Reporter' role is MISSING.\n";
        echo "Creating 'Reporter' role...\n";
        Role::create(['name' => 'Reporter']);
        echo "[FIXED] 'Reporter' role created.\n";
    }
} catch (\Exception $e) {
    echo "[CRITICAL ERROR] Failed to access Roles: " . $e->getMessage() . "\n";
}

echo "=== CHECK COMPLETE ===\n";
