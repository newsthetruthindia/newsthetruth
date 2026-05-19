<?php
$host = '117.252.16.132';
$user = 'root';
$pass = '$9T%Lk057bzu';

// Commands to run on the VPS
$commands = [
    "sed -i '/REVALIDATION_TOKEN=/d' /var/www/ntt/.env",
    "echo 'REVALIDATION_TOKEN=ntt_revalidate_2026_safe_token' >> /var/www/ntt/.env",
    "sed -i '/NEXT_PUBLIC_SITE_URL=/d' /var/www/ntt/.env",
    "echo 'NEXT_PUBLIC_SITE_URL=https://newsthetruth.com' >> /var/www/ntt/.env",
    "cd /var/www/ntt && bash upgrade_backend_vps.sh"
];

$full_cmd = implode(' && ', $commands);
$ssh_cmd = 'ssh -tt -o StrictHostKeyChecking=no -o PreferredAuthentications=password ' . $user . '@' . $host . ' "' . $full_cmd . '"';

$descriptorspec = array(
    0 => array("pipe", "r"),
    1 => array("pipe", "w"),
    2 => array("pipe", "w")
);

echo "Starting production update on VPS...\n";
$process = proc_open($ssh_cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    sleep(3);
    fwrite($pipes[0], $pass . "\n");
    fflush($pipes[0]);
    
    // Deployment can take a while (composer install, etc.)
    $output = "";
    while (!feof($pipes[1])) {
        $line = fgets($pipes[1]);
        echo $line;
        $output .= $line;
    }

    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
    echo "\nProduction update finished.\n";
}
?>
