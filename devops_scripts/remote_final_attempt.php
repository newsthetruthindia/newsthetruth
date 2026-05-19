<?php
// remote_final_attempt.php
// Highly logged SSH script to deploy to VPS.

$host = '117.252.16.132';
$user = 'root';
$pass = '$9T%Lk057bzu';

// We run the pull and the upgrade script
$remoteCmd = "cd /var/www/ntt && git pull origin main && bash upgrade_backend_vps.sh";
$sshCmd = "ssh -tt -o StrictHostKeyChecking=no -o PreferredAuthentications=password $user@$host \"$remoteCmd\"";

echo "🚀 Starting Deployment to $host...\n";

$descriptorspec = [
   0 => ["pipe", "r"],
   1 => ["pipe", "w"],
   2 => ["pipe", "w"]
];

$process = proc_open($sshCmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    // Wait for the password prompt
    $buffer = "";
    $passwordSent = false;
    
    // Set non-blocking to prevent hanging
    stream_set_blocking($pipes[1], 0);
    stream_set_blocking($pipes[2], 0);

    $start = time();
    while (time() - $start < 60) { // 60 second timeout
        $out = fread($pipes[1], 1024);
        $err = fread($pipes[2], 1024);
        
        if ($out) {
            echo "OUT: $out";
            $buffer .= $out;
        }
        if ($err) {
            echo "ERR: $err";
        }

        if (!$passwordSent && (str_contains($buffer, 'password:') || str_contains($buffer, 'Password:'))) {
            echo "\n🔑 Sending password...\n";
            fwrite($pipes[0], $pass . "\n");
            fflush($pipes[0]);
            $passwordSent = true;
        }

        if (str_contains($buffer, 'Deployment Complete!')) {
            echo "\n✨ SUCCESS DETECTED!\n";
            break;
        }

        usleep(100000); // 0.1s
    }

    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
    echo "\n🏁 Process finished.\n";
}
?>
