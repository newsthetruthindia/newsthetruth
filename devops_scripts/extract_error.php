<?php
$logFile = '/var/www/ntt/storage/logs/laravel.log';
if (!file_exists($logFile)) {
    die("Log file not found at $logFile\n");
}

$lines = file($logFile);
$lastError = '';
$found = false;

// Search from bottom up for the last "local.ERROR"
for ($i = count($lines) - 1; $i >= 0; $i--) {
    if (str_contains($lines[$i], 'local.ERROR')) {
        $lastError = $lines[$i];
        // Capture the next few lines which usually contain the message
        for ($j = 1; $j <= 5; $j++) {
            if (isset($lines[$i + $j])) {
                $lastError .= $lines[$i + $j];
            }
        }
        $found = true;
        break;
    }
}

if ($found) {
    echo "LATEST ERROR FOUND:\n";
    echo "===================\n";
    echo $lastError;
    echo "===================\n";
} else {
    echo "No 'local.ERROR' found in the log.\n";
    // Try searching for any exception
    for ($i = count($lines) - 1; $i >= 0; $i--) {
        if (str_contains($lines[$i], 'Stack trace:')) {
            // Error is usually above the stack trace
            echo "STACK TRACE FOUND at line $i. Context:\n";
            for ($k = max(0, $i - 5); $k <= $i; $k++) {
                echo $lines[$k];
            }
            break;
        }
    }
}
