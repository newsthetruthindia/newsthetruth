<?php
// remote_deploy_final.php
// Directly logs into the VPS via SSH to pull code, update .env, and run migrations.

$host = '117.252.16.132';
$user = 'root';
$pass = '$9T%Lk057bzu';

$restApiKey = 'os_v2_app_ede4m1rq6rblda4fmfceq45bvu3o2os7wl4u6suaaolwoju2z33fvnnyivjeltr7hnwmwub2zj3b4w6qwrj7xy2lctbcrhfkfw54p3a';
$appId = '20c9c62e-30f4-42b1-8385-61444873a1ad';

// Commands to run on the VPS
$commands = [
    "cd /var/www/ntt",
    "git pull origin main",
    // Update .env with OneSignal keys using sed
    "sed -i 's/^ONESIGNAL_APP_ID=.*/ONESIGNAL_APP_ID=$appId/' .env",
    "sed -i 's/^ONESIGNAL_REST_API_KEY=.*/ONESIGNAL_REST_API_KEY=$restApiKey/' .env",
    // Run the upgrade script
    "bash upgrade_backend_vps.sh"
];

$fullCmdString = implode(" && ", $commands);
$sshCmd = 'ssh -tt -o StrictHostKeyChecking=no -o PreferredAuthentications=password ' . $user . '@' . $host . ' "' . $fullCmdString . '"';

echo "🚀 Starting Remote Deployment to VPS ($host)...\n";

$descriptorspec = [
    0 => ["pipe", "r"],
    1 => ["pipe", "w"],
    2 => ["pipe", "w"]
];

$process = proc_open($sshCmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    sleep(2);
    // Send password
    fwrite($pipes[0], $pass . "\n");
    fflush($pipes[0]);
    
    // Set a long timeout for the upgrade script
    stream_set_timeout($pipes[1], 120);
    stream_set_timeout($pipes[2], 120);

    echo "📡 Connection established. Executing deployment...\n\n";

    while (!feof($pipes[1])) {
        echo fgets($pipes[1]);
    }
    while (!feof($pipes[2])) {
        echo fgets($pipes[2]);
    }

    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
    
    echo "\n✅ Remote Deployment Finished!\n";
} else {
    echo "❌ Failed to initiate SSH process.\n";
}
?>
