<?php
// restore_reporters.php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;
use App\Models\User;

$reporters = User::role('Reporter')->get();
$count = 0;

foreach ($reporters as $reporter) {
    if (!$reporter->firstname) continue;

    $fullName = trim($reporter->firstname . ' ' . $reporter->lastname);
    
    // We only update posts that are currently owned by someone else (Admin)
    // but have this reporter's name in the reporter_name column
    $updated = Post::where('reporter_name', clone \Illuminate\Support\Str::of($fullName)->trim())
        ->update(['user_id' => $reporter->id]);
        
    echo "Restored $updated posts to $fullName\n";
    $count += $updated;
}

echo "Total restored: $count\n";
