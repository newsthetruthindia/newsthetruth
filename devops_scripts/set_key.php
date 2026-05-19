<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$m = App\Models\UserMonitor::first();
if ($m) {
    $m->secret_key = 'monitor';
    $m->save();
    echo "SUCCESS\n";
} else {
    echo "NOT FOUND\n";
}
