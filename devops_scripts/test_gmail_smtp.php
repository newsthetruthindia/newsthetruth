<?php
require '/var/www/ntt/vendor/autoload.php';
$app = require_once '/var/www/ntt/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "SMTP: " . config('mail.mailers.smtp.host') . ":" . config('mail.mailers.smtp.port') . "/" . config('mail.mailers.smtp.encryption') . PHP_EOL;
echo "From: " . config('mail.from.address') . PHP_EOL;

try {
    \Illuminate\Support\Facades\Mail::raw('Gmail SMTP test from NTT VPS - ' . date('Y-m-d H:i:s'), function($m) {
        $m->to('newsthetruthindia@gmail.com')->subject('NTT Gmail SMTP Test');
    });
    echo 'SUCCESS: Mail sent via Gmail SMTP!' . PHP_EOL;
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
