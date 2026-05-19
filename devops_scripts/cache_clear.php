<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
Artisan::call('optimize:clear');
echo Artisan::output();
echo "\nCache Cleared Successfully.";
