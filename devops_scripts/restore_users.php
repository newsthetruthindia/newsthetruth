<?php

use App\Models\User;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting Restoration...\n";

$count = User::onlyTrashed()->count();
User::onlyTrashed()->restore();

echo "Restored {$count} users.\n";
echo "Total active users: " . User::count() . "\n";
echo "Restoration Complete.\n";
