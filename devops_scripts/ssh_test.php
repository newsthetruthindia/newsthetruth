<?php
// ssh_test.php
$host = '117.252.16.132';
$user = 'root';
$pass = '$9T%Lk057bzu';

// Use a simple command to test connectivity
$cmd = 'ssh -tt -o StrictHostKeyChecking=no -o PreferredAuthentications=password ' . $user . '@' . $host . ' "uptime"';

echo "Testing SSH uptime...\n";
$descriptorspec = [
    0 => ["pipe", "r"],
    1 => ["pipe", "w"],
    2 => ["pipe", "w"]
];

$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    sleep(1);
    fwrite($pipes[0], $pass . "\n");
    fflush($pipes[0]);
    sleep(5);
    echo "STDOUT: " . stream_get_contents($pipes[1]) . "\n";
    echo "STDERR: " . stream_get_contents($pipes[2]) . "\n";
    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
}
?>
