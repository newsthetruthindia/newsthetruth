<?php
$users = \App\Models\User::role('Reporter')
    ->get()
    ->map(fn($u) => [
        'id' => $u->id,
        'email' => $u->email,
        'name' => $u->firstname . ' ' . $u->lastname,
        'designation' => $u->details->designation ?? 'N/A',
        'type' => $u->type,
    ])
    ->toArray();

echo "REPORTERS WITH ROLE:\n";
print_r($users);

$allNtt = \App\Models\User::where('firstname', 'like', '%NTT%')
    ->orWhere('lastname', 'like', '%NTT%')
    ->get()
    ->map(fn($u) => [
        'id' => $u->id,
        'email' => $u->email,
        'name' => $u->firstname . ' ' . $u->lastname,
        'designation' => $u->details->designation ?? 'N/A',
        'roles' => $u->getRoleNames(),
        'type' => $u->type,
    ])
    ->toArray();

echo "\nALL NTT MATCHES:\n";
print_r($allNtt);
