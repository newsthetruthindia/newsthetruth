<?php
// ssh_direct.php
$host = '117.252.16.132';
$user = 'root';
$pass = '$9T%Lk057bzu';

$cmd = "ssh -o StrictHostKeyChecking=no -o PreferredAuthentications=password $user@$host 'uptime'";

echo "Running: $cmd\n";
$descriptorspec = [
   0 => ["pipe", "r"],
   1 => ["pipe", "w"],
   2 => ["pipe", "w"]
];

$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    // Some SSH clients on Windows might need a small delay before reading the prompt
    sleep(1);
    fwrite($pipes[0], $pass . "\n");
    fflush($pipes[0]);
    
    // Read output
    $output = "";
    while (!feof($pipes[1])) {
        $line = fgets($pipes[1]);
        if ($line === false) break;
        echo "OUT: $line";
        $output .= $line;
    }
    while (!feof($pipes[2])) {
        $line = fgets($pipes[2]);
        if ($line === false) break;
        echo "ERR: $line";
    }

    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
}
?>
