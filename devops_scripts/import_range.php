<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
$sql = file_get_contents("/tmp/range_sync.txt");
if ($sql) {
    echo "Processing SQL...\n";
    DB::unprepared($sql);
    echo "SUCCESS\n";
} else {
    echo "EMPTY SQL FILE\n";
}
?>
