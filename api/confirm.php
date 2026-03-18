<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>FINAL CONFIRMATION - FIX APPLIED</h1>";

try {
    echo "1. Autoloader... ";
    require __DIR__ . '/../vendor/autoload.php';
    echo "OK<br>";

    echo "2. App Instance... ";
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "OK<br>";

    echo "Manifest Path Audit:<br>";
    $manifest = $app->make(Illuminate\Foundation\PackageManifest::class);
    echo " - Path: " . $manifest->manifestPath . "<br>";
    echo " - Is writable: " . (is_writable(dirname($manifest->manifestPath)) ? "YES" : "NO") . "<br>";

    echo "SUCCESS: If you see this, the site can boot!<br>";

} catch (\Throwable $e) {
    echo "<br><b style='color:red'>FATAL ERROR:</b> " . $e->getMessage() . "<br>";
}
echo "<h1>Diagnostic End</h1>";
?>
