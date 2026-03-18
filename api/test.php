<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Vercel Filesystem Audit</h1>";

$paths = [
    'root' => '/var/task/user',
    'bootstrap' => '/var/task/user/bootstrap',
    'cache' => '/var/task/user/bootstrap/cache',
    'tmp' => '/tmp'
];

foreach ($paths as $name => $path) {
    echo "Path [$name]: $path <br>";
    echo " - Exists: " . (file_exists($path) ? "YES" : "NO") . "<br>";
    if (file_exists($path)) {
        echo " - Is Directory: " . (is_dir($path) ? "YES" : "NO") . "<br>";
        echo " - Is Writable: " . (is_writable($path) ? "YES" : "NO") . "<br>";
        if (is_dir($path)) {
            echo " - Contents: <br>";
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    echo "   -- $file<br>";
                }
            }
        }
    }
    echo "<hr>";
}

echo "<h1>Diagnostic End</h1>";
?>
