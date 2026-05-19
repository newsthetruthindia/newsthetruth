param(
    [string]$Command
)

$sshKey = "C:\Users\HPi9\.ssh\id_ed25519_ntt"
$vpsUser = "root"
$vpsHost = "117.252.16.132"

ssh -i $sshKey -o StrictHostKeyChecking=no "$vpsUser@$vpsHost" $Command
