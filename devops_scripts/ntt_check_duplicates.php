<?php
$user395 = \App\Models\User::find(395);
$user397 = \App\Models\User::find(397);

echo "--- User 395 ({$user395->email}) ---\n";
echo "Type: {$user395->type}\n";
echo "Posts Count: " . \App\Models\Post::where('user_id', 395)->count() . "\n";
echo "Roles: " . json_encode($user395->roles->pluck('name')) . "\n\n";

echo "--- User 397 ({$user397->email}) ---\n";
echo "Type: {$user397->type}\n";
echo "Posts Count: " . \App\Models\Post::where('user_id', 397)->count() . "\n";
echo "Roles: " . json_encode($user397->roles->pluck('name')) . "\n\n";
