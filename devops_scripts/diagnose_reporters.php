<?php
$users = \App\Models\User::role('Reporter')->get(['id', 'firstname', 'lastname', 'email'])->toArray();
$nttUsers = \App\Models\User::where('firstname', 'like', '%NTT%')
    ->orWhere('lastname', 'like', '%NTT%')
    ->get(['id', 'firstname', 'lastname', 'email'])
    ->toArray();

echo "REPORTERS:\n";
print_r($users);
echo "\nNTT MATCHES:\n";
print_r($nttUsers);

$staff = \App\Models\User::role('Staff')->get(['id', 'firstname', 'lastname', 'email'])->toArray();
echo "\nSTAFF:\n";
print_r($staff);
