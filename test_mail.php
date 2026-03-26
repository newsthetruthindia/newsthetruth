<?php

use Illuminate\Support\Facades\Mail;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Mail Configuration...\n";
echo "Host: " . env('MAIL_HOST') . "\n";
echo "Port: " . env('MAIL_PORT') . "\n";
echo "User: " . env('MAIL_USERNAME') . "\n";

try {
    Mail::raw('Testing NTT Mail System at ' . now(), function ($message) {
        $message->to('newsthetruthindia@gmail.com')
                ->subject('NTT Mail Test');
    });
    echo "SUCCESS: Mail sent according to Laravel.\n";
} catch (\Exception $e) {
    echo "FAILURE: " . $e->getMessage() . "\n";
}
