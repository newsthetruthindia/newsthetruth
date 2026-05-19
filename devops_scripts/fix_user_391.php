<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$user = User::find(391);
if ($user) {
    $user->update(['type' => 'employee']);
    echo "User 391 updated to employee successfully.\n";
} else {
    echo "User 391 not found.\n";
}
?>
