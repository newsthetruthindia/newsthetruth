<?php
// ssh_legacy_style.php
$host = '117.252.16.132';
$user = 'root';
$pass = '$9T%Lk057bzu';

$remoteCmd = "cd /var/www/ntt && git pull origin main && bash upgrade_backend_vps.sh";
$cmd = 'ssh -tt -o StrictHostKeyChecking=no -o PreferredAuthentications=password ' . $user . '@' . $host . ' "' . $remoteCmd . '"';

$descriptorspec = [
    0 => ["pipe", "r"],
    1 => ["pipe", "w"],
    2 => ["pipe", "w"]
];

echo "Connecting legacy style...\n";
$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    sleep(5); // Wait for connection/prompt
    fwrite($pipes[0], $pass . "\n");
    fflush($pipes[0]);
    echo "Password sent.\n";
    sleep(15); // Wait for command to start

    echo "STDOUT: " . stream_get_contents($pipes[1]) . "\n";
    echo "STDERR: " . stream_get_contents($pipes[2]) . "\n";

    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
}
?>
