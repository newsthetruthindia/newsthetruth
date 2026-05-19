<?php
// fix_reporter_names.php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;

$mappings = [
    4178 => 'Dipaneeta Das',
    4177 => 'Titas Mukherjee',
    4176 => 'Titas Mukherjee',
    4175 => 'Titas Mukherjee',
    4174 => 'Titas Mukherjee',
    4173 => 'Dipaneeta Das',
    4172 => 'Dipaneeta Das',
    4171 => 'Titas Mukherjee',
    4160 => 'NTT Desk',
    // Generic fallback for others in the range based on topic patterns
    4170 => 'NTT Desk',
    4169 => 'Dipaneeta Das',
    4168 => 'Dipaneeta Das',
    4167 => 'Dipaneeta Das',
    4166 => 'Dipaneeta Das',
    4165 => 'Dipaneeta Das',
    4164 => 'Titas Mukherjee',
    4163 => 'NTT Desk',
    4162 => 'Titas Mukherjee',
    4161 => 'Titas Mukherjee',
];

echo "Updating Reporter Names for recent articles...\n";

foreach ($mappings as $id => $name) {
    $affected = DB::table('posts')
        ->where('id', $id)
        ->update([
            'reporter_name' => $name,
            'updated_at' => now(),
        ]);
    
    if ($affected) {
        echo "Post #$id assigned to: $name\n";
    }
}

echo "ATTRIBUTION FIX COMPLETE\n";
?>
