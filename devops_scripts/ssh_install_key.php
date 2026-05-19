<?php
$host = '117.252.16.132';
$user = 'root';
$pass = '$9T%Lk057bzu';
$cmd = 'ssh -tt -o StrictHostKeyChecking=no -o PreferredAuthentications=password ' . $user . '@' . $host . ' "mkdir -p ~/.ssh && echo \'ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAIF5dvd77ezk99buwynA3XHNyB8TR/MtAo7RH9OhSlA1+ hpi9@RonySantraNTT\' >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys && echo KEY_SUCCESS"';

$descriptorspec = array(
    0 => array("pipe", "r"),
    1 => array("pipe", "w"),
    2 => array("pipe", "w")
);

$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    sleep(3);
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