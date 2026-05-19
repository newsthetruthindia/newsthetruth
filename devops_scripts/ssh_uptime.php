<?php
// ssh_uptime.php
$host = '117.252.16.132';
$user = 'root';
$pass = '$9T%Lk057bzu';

$cmd = 'ssh -tt -o StrictHostKeyChecking=no -o PreferredAuthentications=password ' . $user . '@' . $host . ' "uptime"';

$descriptorspec = [
    0 => ["pipe", "r"],
    1 => ["pipe", "w"],
    2 => ["pipe", "w"]
];

echo "Connecting for uptime...\n";
$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    sleep(5);
    fwrite($pipes[0], $pass . "\n");
    fflush($pipes[0]);
    sleep(10);
    echo "STDOUT: " . stream_get_contents($pipes[1]) . "\n";
    echo "STDERR: " . stream_get_contents($pipes[2]) . "\n";
    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
}
?>
