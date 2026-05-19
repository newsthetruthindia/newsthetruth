$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"
$sql = "SELECT title FROM posts WHERE created_at LIKE '2023%' LIMIT 5;"

$command = "mysql -u newstew1_newsthet -p'3RdX?tPig*^$' newstew1_main -N -s -e \`"$sql\`""

ssh -i $sshKey -o StrictHostKeyChecking=no "${vpsUser}@${vpsHost}" $command
