<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Step-by-Step Laravel Bootstrap</h1>";

try {
    echo "1. Autoloader... ";
    require __DIR__ . '/../vendor/autoload.php';
    echo "OK<br>";

    echo "2. App Instance... ";
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "OK<br>";

    $bootstrappers = [
        'LoadEnvironmentVariables' => \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
        'LoadConfiguration'        => \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
        'HandleExceptions'        => \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        'RegisterFacades'         => \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
        'RegisterProviders'       => \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
        'BootProviders'           => \Illuminate\Foundation\Bootstrap\BootProviders::class,
    ];

    foreach ($bootstrappers as $name => $class) {
        echo "Attempting $name... ";
        $app->make($class)->bootstrap($app);
        echo "OK<br>";
    }

    echo "<h3>System Final Info:</h3>";
    echo "APP_KEY Fallback: " . (config('app.key') ? "VALID" : "EMPTY") . "<br>";
    echo "DB Host Fallback: " . config('database.connections.mysql.host') . "<br>";
    echo "Current Env: " . app()->environment() . "<br>";

} catch (\Throwable $e) {
    echo "<br><b style='color:red; font-size: 20px;'>BOOTSTRAP FAILED!</b><br>";
    echo "<b>Message:</b> " . $e->getMessage() . "<br>";
    echo "<b>File:</b> " . $e->getFile() . " on line " . $e->getLine() . "<br>";
    echo "<br><b>Stack Trace:</b><pre style='background:#f4f4f4; padding:10px; border:1px solid #ccc; overflow:auto;'>" . $e->getTraceAsString() . "</pre>";
}

echo "<h1>Diagnostic End</h1>";
?>
