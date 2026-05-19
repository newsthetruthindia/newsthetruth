<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/admin/login', 'GET');

try {
    $response = $kernel->handle($request);
    echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
    if ($response->getStatusCode() === 403) {
        echo $response->getContent();
    }
} catch (\Throwable $e) {
    echo 'Exception: ' . get_class($e) . ' - ' . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString();
}
echo PHP_EOL;
