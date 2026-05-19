<?php
// restore_admin_ownership.php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;
use App\Models\User;

// Users who definitely post
$allowedPosters = [
    'santrarony9@gmail.com',
    'arko.arbd@gmail.com',
    'tridib.sardar.294@gmail.com',
    'dasdipaneeta97@gmail.com',
    'newsthetruthindia@gmail.com' // Admin
];

$allowedPosterIds = User::whereIn('email', $allowedPosters)->pluck('id')->toArray();

if (empty($allowedPosterIds)) {
    echo "No allowed posters found. Aborting.\n";
    exit;
}

$adminId = 390;

// Reassign any post NOT posted by an allowed poster, back to Admin
$updated = Post::whereNotIn('user_id', $allowedPosterIds)
    ->update(['user_id' => $adminId]);

echo "Restored {$updated} posts to Admin (Posted By) ownership.\n";
