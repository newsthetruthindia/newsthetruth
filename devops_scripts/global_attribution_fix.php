<?php
/**
 * NTT Global Attribution Corrector
 * Ensures that every article's user_id matches the person named in the reporter_name metadata.
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;
use App\Models\User;

echo "--- Starting Global Attribution Correction ---\n";

// Fetch all users who are marked as reporters or are well-known
$reporters = User::all();

foreach ($reporters as $rep) {
    $name = trim($rep->firstname . ' ' . $rep->lastname);
    
    // We look for articles that have this reporter name but are NOT owned by this user ID.
    // We skip 'Admin' or 'NTT Desk' generic names.
    if (!empty($name) && strlen($name) > 3) {
        $affected = Post::where('reporter_name', 'LIKE', '%' . $name . '%')
            ->where('user_id', '!=', $rep->id)
            ->update(['user_id' => $rep->id]);
            
        if ($affected > 0) {
            echo "Successfully reassigned $affected posts to $name (User ID: {$rep->id})\n";
        }
    }
}

// Special case: If reporter_name is 'NTT Desk', we should probably map it to the Admin account if not already.
$admin = User::where('id', 390)->first();
if ($admin) {
    $affected = Post::where('reporter_name', 'NTT Desk')
        ->where('user_id', '!=', 390)
        ->update(['user_id' => 390]);
    if ($affected > 0) {
        echo "Successfully mapped $affected 'NTT Desk' posts to Admin (User ID: 390).\n";
    }
}

echo "--- Global Attribution Correction Complete ---\n";
