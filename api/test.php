<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Deep Manifest Diagnostic</h1>";

try {
    echo "1. Autoloader... ";
    require __DIR__ . '/../vendor/autoload.php';
    echo "OK<br>";

    echo "2. App Instance... ";
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "OK<br>";

    echo "3. Resolving PackageManifest... ";
    $manifest = $app->make(Illuminate\Foundation\PackageManifest::class);
    echo "OK<br>";

    echo "Manifest Reference: " . get_class($manifest) . "<br>";
    echo "Manifest Path: " . $manifest->manifestPath . "<br>";
    
    $dirname = dirname($manifest->manifestPath);
    echo "Dirname: " . $dirname . "<br>";
    echo "Dir Exists: " . (file_exists($dirname) ? "YES" : "NO") . "<br>";
    echo "Dir Writable: " . (is_writable($dirname) ? "YES" : "NO") . "<br>";

    if (!is_file($manifest->manifestPath)) {
        echo "Manifest file missing. Attempting to build... <br>";
        // This will trigger the write() call
        // $manifest->build(); 
        // We'll try to write it ourselves first to test /tmp
        echo "Testing manual write to /tmp/test_write.txt... ";
        if (file_put_contents('/tmp/test_write.txt', 'test')) {
            echo "SUCCESS<br>";
        } else {
            echo "FAILED (Error: " . error_get_last()['message'] . ")<br>";
        }
    }

} catch (\Throwable $e) {
    echo "<br><b style='color:red'>FATAL ERROR:</b> " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h1>Diagnostic End</h1>";
?>
