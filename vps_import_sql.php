<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Starting DB unprepared import...\n";
$sql = file_get_contents('/var/www/ntt/history_dump.sql');
\DB::unprepared($sql);
echo "Import complete!\n";
