<?php

// NTT Migration Fix Script
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $exists = DB::table('migrations')->where('migration', '2023_07_01_141620_create_citizen_journalisms_table')->exists();
    
    if (!$exists) {
        DB::table('migrations')->insert([
            'migration' => '2023_07_01_141620_create_citizen_journalisms_table',
            'batch' => (DB::table('migrations')->max('batch') ?: 0) + 1
        ]);
        echo "Migration record inserted successfully.\n";
    } else {
        echo "Migration record already exists.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
