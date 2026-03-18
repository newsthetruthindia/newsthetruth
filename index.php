<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// DEBUG: Force error display for Vercel troubleshooting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

// Vercel read-only filesystem fix
if (isset($_SERVER['VERCEL_URL']) || isset($_ENV['VERCEL_URL'])) {
    if (!defined('VERCEL_FIX_APPLIED')) {
        define('VERCEL_FIX_APPLIED', true);
        
        // Redirect Storage
        $_ENV['APP_STORAGE'] = '/tmp/storage';
        $_SERVER['APP_STORAGE'] = '/tmp/storage';
        
        // Redirect ALL Cache Files (Definitive Fix)
        $_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
        $_SERVER['APP_SERVICES_CACHE'] = '/tmp/services.php';
        $_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
        $_SERVER['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
        $_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
        $_SERVER['APP_CONFIG_CACHE'] = '/tmp/config.php';
        $_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
        $_SERVER['APP_ROUTES_CACHE'] = '/tmp/routes.php';
        $_ENV['APP_EVENTS_CACHE'] = '/tmp/events.php';
        $_SERVER['APP_EVENTS_CACHE'] = '/tmp/events.php';
        
        // Create necessary storage directories
        foreach ([
            '/tmp/storage/framework/cache', 
            '/tmp/storage/framework/sessions', 
            '/tmp/storage/framework/views', 
            '/tmp/storage/logs'
        ] as $path) {
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }
    }
}

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
