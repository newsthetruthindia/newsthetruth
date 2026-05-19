<?php
// remote_vps_update_final.php
$host = '117.252.16.132';
$user = 'root';
$pass = '$9T%Lk057bzu';

// The full command to update everything
$remoteCmd = "cd /var/www/ntt && git pull origin main && bash upgrade_backend_vps.sh";
$cmd = 'ssh -tt -o StrictHostKeyChecking=no -o PreferredAuthentications=password ' . $user . '@' . $host . ' "' . $remoteCmd . '"';

$descriptorspec = [
    0 => ["pipe", "r"],
    1 => ["pipe", "w"],
    2 => ["pipe", "w"]
];

echo "🚀 Connecting to VPS at $host as $user...\n";
$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    // Increase wait time for the prompt
    sleep(10); 
    echo "🔑 Sending password...\n";
    fwrite($pipes[0], $pass . "\n");
    fflush($pipes[0]);
    
    echo "📡 Deployment started. This may take a minute...\n\n";

    // Read output in a loop to see progress
    stream_set_blocking($pipes[1], 0);
    stream_set_blocking($pipes[2], 0);

    $start = time();
    while (time() - $start < 300) { // 5 minute total timeout
        $out = fread($pipes[1], 4096);
        $err = fread($pipes[2], 4096);
        
        if ($out) echo $out;
        if ($err) echo "ERR: " . $err;

        if (str_contains($out, 'Deployment Complete!')) {
            echo "\n✨ SUCCESS: Backend is updated!\n";
            break;
        }
        
        // If the process is no longer running, exit
        $status = proc_get_status($process);
        if (!$status['running']) break;

        usleep(200000); // 0.2s
    }

    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
    echo "\n🏁 Deployment process finished.\n";
}
?>
