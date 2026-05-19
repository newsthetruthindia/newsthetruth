<?php
use App\Models\User;
use App\Models\Post;

// 1. Move posts from 395 to 397
$moved = Post::where('user_id', 395)->update(['user_id' => 397]);
echo "Moved {$moved} posts to the main NTT Desk (397).\n";

// 2. Fix the fake NTT Desk (395)
$user395 = User::find(395);
if ($user395) {
    $user395->firstname = 'NTT';
    $user395->lastname = 'Feedback';
    $user395->save();
    $user395->removeRole('Reporter');
    echo "Removed Reporter role and renamed 395 to NTT Feedback.\n";
}

// 3. Fix the real NTT Desk (397)
$user397 = User::find(397);
if ($user397) {
    $user397->type = 'employee';
    $user397->save();
    echo "Updated 397 type to 'employee' so it appears in Staff Members.\n";
}
