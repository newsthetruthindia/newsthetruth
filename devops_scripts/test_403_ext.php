<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/admin/login', 'GET', [], [], [], [
    'REMOTE_ADDR' => '49.37.39.106',
    'HTTP_HOST' => '117.252.16.132'
]);

try {
    $response = $kernel->handle($request);
    echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
    if ($response->getStatusCode() === 403) {
        echo substr($response->getContent(), 0, 800); 
    }
} catch (\Throwable $e) {
    echo 'Exception: ' . get_class($e) . ' - ' . $e->getMessage() . PHP_EOL;
}
echo PHP_EOL;
