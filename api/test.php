<?php
// ERROR TRAPPER
$logFile = '/tmp/vercel_debug.log';

function ntt_log($msg) {
    global $logFile;
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $msg . "\n", FILE_APPEND);
}

set_exception_handler(function($e) {
    ntt_log("EXCEPTION: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    ntt_log("STACK: " . $e->getTraceAsString());
    echo "<h1>FATAL EXCEPTION TRAPPED</h1>";
    echo $e->getMessage();
});

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    ntt_log("ERROR ($errno): $errstr in $errfile:$errline");
    return false;
});

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && ($error['type'] === E_ERROR || $error['type'] === E_PARSE || $error['type'] === E_CORE_ERROR || $error['type'] === E_COMPILE_ERROR)) {
        ntt_log("SHUTDOWN ERROR: " . $error['message'] . " in " . $error['file'] . ":" . $error['line']);
    }
});

echo "<h1>Runtime Log Reader</h1>";

if (isset($_GET['clear'])) {
    unlink($logFile);
    echo "Log cleared.<br>";
}

if (file_exists($logFile)) {
    echo "<h3>Live Log Content:</h3>";
    echo "<pre style='background:#000; color:#0f0; padding:10px;'>" . htmlspecialchars(file_get_contents($logFile)) . "</pre>";
} else {
    echo "No log found yet. Try hitting the main site or booting Laravel here.<br>";
}

echo "<hr>";
echo "<h3>Triggering Laravel Boot...</h3>";

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    // Explicitly boot
    $app->make(Illuminate\Foundation\Bootstrap\LoadConfiguration::class)->bootstrap($app);
    $app->make(Illuminate\Foundation\Bootstrap\RegisterFacades::class)->bootstrap($app);
    
    echo "Laravel Partials Booted OK.<br>";
    echo "APP_KEY: " . config('app.key') . "<br>";
} catch (\Throwable $e) {
    ntt_log("BOOT ERROR: " . $e->getMessage());
    echo "Boot failed. Check log above.";
}
?>
