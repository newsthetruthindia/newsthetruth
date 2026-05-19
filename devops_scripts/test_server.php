<?php
header('Content-Type: text/plain');
echo "=== NGINX to PHP-FPM REQUEST VERIFICATION ===\n\n";

$keys = [
    'REQUEST_METHOD',
    'REQUEST_URI',
    'SCRIPT_NAME',
    'SCRIPT_FILENAME',
    'DOCUMENT_ROOT',
    'HTTP_HOST',
    'REMOTE_ADDR',
    'HTTPS'
];

foreach ($keys as $key) {
    $val = isset($_SERVER[$key]) ? $_SERVER[$key] : '(not set)';
    echo str_pad($key, 20) . " : " . $val . "\n";
}

echo "\nFULL ARRAY:\n";
print_r($_SERVER);
