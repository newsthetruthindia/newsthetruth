$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"
$sql = "SELECT COUNT(id) FROM posts WHERE created_at LIKE '2023%';"

$command = "mysql -u newstew1_newsthet -p'3RdX?tPig*^$' newstew1_main -N -s -e \`"$sql\`""

# We need to wrap the whole command in double quotes for SSH, and escape the inner double quotes
ssh -i $sshKey -o StrictHostKeyChecking=no "${vpsUser}@${vpsHost}" $command
