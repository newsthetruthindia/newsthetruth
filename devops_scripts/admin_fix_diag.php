<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

$email = 'newsthetruthindia@gmail.com';

echo "--- Admin User Check ---\n";
// 1. Direct DB Query to bypass model discrepancies
$dbUser = DB::table('users')->where('email', $email)->first();

if ($dbUser) {
    echo "User found in 'users' table (ID: {$dbUser->id})\n";
    echo "Email: {$dbUser->email}\n";
    echo "Type: " . ($dbUser->type ?? 'NULL') . "\n";
    
    // 2. Check Spatie roles tables if they exist
    $hasRolesTable = Schema::hasTable('roles');
    if ($hasRolesTable) {
        $roles = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_id', $dbUser->id)
            ->where('model_type', 'App\Models\User')
            ->pluck('name');
        echo "Spatie Roles: " . ($roles->isEmpty() ? "NONE" : $roles->implode(', ')) . "\n";
    } else {
        echo "Spatie 'roles' table not found.\n";
    }
} else {
    echo "User NOT FOUND in 'users' table.\n";
    // List all admins
    $admins = DB::table('users')->where('type', 'admin')->get(['id', 'email']);
    if ($admins->isNotEmpty()) {
        echo "Found other admins:\n";
        foreach ($admins as $admin) {
            echo "- {$admin->email} (ID: {$admin->id})\n";
        }
    } else {
        echo "No users with type='admin' found.\n";
    }
}

echo "\n--- Mail Configuration Check ---\n";
echo "MAIL_MAILER: " . config('mail.default') . "\n";
echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";

echo "\n--- Sending Test Mail to {$email} ---\n";
try {
    Mail::raw("NTT Diagnostic: This is a test to verify SMTP on port 465 with current settings.", function ($message) use ($email) {
        $message->to($email)->subject('NTT SMTP Verification');
    });
    echo "SUCCESS: Test mail sent without errors.\n";
} catch (\Exception $e) {
    echo "FAILURE: " . $e->getMessage() . "\n";
}
