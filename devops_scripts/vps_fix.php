<?php
ini_set('memory_limit', '512M');
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

echo "--- NTT VPS FIX SCRIPT ---\n";

use App\Models\User;
use App\Models\Post;
use App\Models\SiteSetting;

// Fix Admin User
$admin = User::where('email', 'admin@ntt.com')->first();
if ($admin) {
    echo "Fixing Admin User: {$admin->email}\n";
    $admin->type = 'admin';
    $admin->password = bcrypt('password123');
    $admin->save();
    echo "Admin User Updated. Type: {$admin->type}\n";
} else {
    echo "Admin user not found. Creating...\n";
    User::create([
        'firstname' => 'Admin',
        'lastname' => 'NTT',
        'email' => 'admin@ntt.com',
        'password' => bcrypt('password123'),
        'type' => 'admin',
    ]);
    echo "Admin user created.\n";
}

// Debug Homepage 500
try {
    $settings = SiteSetting::pluck('description', 'key')->toArray();
    echo "Site Settings count: " . count($settings) . "\n";
    
    $top_posts = Post::where('top_post', 1)->where('status', 'published')->limit(5)->get();
    echo "Top Posts count: " . $top_posts->count() . "\n";
    
    echo "Homepage check passed.\n";
} catch (\Exception $e) {
    echo "HOMEPAGE ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "--- SCRIPT END ---\n";
