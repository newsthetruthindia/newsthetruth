$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"
$sql = "SELECT YEAR(created_at) as year, COUNT(*) as count FROM posts GROUP BY year ORDER BY year;"

$command = "mysql -u newstew1_newsthet -p'3RdX?tPig*^$' newstew1_main -N -s -e \`"$sql\`""

ssh -i $sshKey -o StrictHostKeyChecking=no "${vpsUser}@${vpsHost}" $command
