<?php
use App\Models\User;

$user395 = User::find(395);
if ($user395 && $user395->details) {
    $user395->details->update([
        'is_reporter' => 0,
        'designation' => null,
        'bio' => null,
        'attachment_id' => null
    ]);
    echo "Successfully removed NTT Feedback (395) from the Reporter list by clearing its is_reporter flag and designation.\n";
} else {
    echo "No details found for user 395.\n";
}

$user397 = User::find(397);
if ($user397 && $user397->details) {
    if (empty($user397->details->designation)) {
        $user397->details->update(['designation' => 'Official Editorial Desk']);
    }
    echo "Verified NTT Desk (397) designation is active.\n";
}
