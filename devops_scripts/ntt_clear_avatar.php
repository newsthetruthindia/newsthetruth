<?php
$user = \App\Models\User::find(397);
if ($user && $user->details) {
    $user->details->update(['attachment_id' => null]);
    echo "Successfully removed the accidental avatar from NTT Desk.";
} else {
    echo "No details found for user 397.";
}
