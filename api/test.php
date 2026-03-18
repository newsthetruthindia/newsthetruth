<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Detailed Laravel Bootstrap Diagnostic</h1>";

try {
    echo "1. Requiring Autoloader... ";
    require __DIR__ . '/../vendor/autoload.php';
    echo "OK<br>";

    echo "2. Requiring App... ";
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "OK<br>";

    echo "3. Resolving Kernel... ";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "OK<br>";

    echo "4. Attempting Full Boot (Kernel Handle)... ";
    // We simulate a request to trigger the full boot process of Service Providers
    $request = Illuminate\Http\Request::capture();
    
    // We don't want to send the response, just boot the app
    // In Laravel 11, the bootstrap happens inside handle()
    
    echo "Booting... ";
    // This is the moment of truth where Providers are loaded
    $app->boot(); 
    echo "App Booted Successfully!<br>";

    echo "5. Checking Configuration...<br>";
    echo "APP_KEY Fallback Check: " . (config('app.key') ? "VALID" : "EMPTY") . "<br>";
    echo "DB Connection Fallback Check: " . config('database.default') . "<br>";

} catch (\Throwable $e) {
    echo "<br><b style='color:red; font-size: 24px;'>FATAL ERROR CAUGHT:</b><br>";
    echo "<b>Message:</b> " . $e->getMessage() . "<br>";
    echo "<b>File:</b> " . $e->getFile() . " on line " . $e->getLine() . "<br>";
    echo "<b>Code:</b> " . $e->getCode() . "<br>";
    echo "<br><b>Stack Trace:</b><pre style='background:#f4f4f4; padding:10px; border:1px solid #ccc; overflow:auto;'>" . $e->getTraceAsString() . "</pre>";
}

echo "<h1>Diagnostic End</h1>";
?>
