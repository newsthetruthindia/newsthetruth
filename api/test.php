<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnostic Start</h1>";

try {
    echo "Checking vendor/autoload.php... ";
    if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
        echo "FAILED (File not found!)<br>";
    } else {
        require __DIR__ . '/../vendor/autoload.php';
        echo "OK<br>";
    }

    echo "Checking bootstrap/app.php... ";
    if (!file_exists(__DIR__ . '/../bootstrap/app.php')) {
        echo "FAILED (File not found!)<br>";
    } else {
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        echo "OK (App Instance Created)<br>";
    }

    echo "Attempting to resolve Kernel... ";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "OK<br>";

    echo "Checking Laravel Configuration...<br>";
    echo "Config App Key: " . (config('app.key') ? "SET" : "MISSING") . "<br>";
    echo "Env App Key: " . (env('APP_KEY') ? "SET" : "MISSING") . "<br>";
    echo "Config DB Host: " . config('database.connections.mysql.host') . "<br>";

} catch (\Throwable $e) {
    echo "<br><b style='color:red'>FATAL ERROR CATATCHED:</b><br>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "<br>";
    echo "Stack Trace:<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h1>Diagnostic End</h1>";
?>
