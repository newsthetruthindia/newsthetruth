<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Media;

echo "--- User Thumbnail Audit ---\n";

$users = User::whereIn('id', [381, 385])->with('thumbnails')->get();

foreach ($users as $u) {
    echo "ID: {$u->id} | Name: {$u->firstname} {$u->lastname} | Thumbnail_ID: {$u->thumbnail} | Path: " . ($u->thumbnails->path ?? 'NONE') . "\n";
}

// Based on previous observation:
// Dipaneeta (385) has photo uploads/avatars/01KNCEYATEH7J4RTX1JDG78QPN.jpg (Female)
// Ankit (381) might be missing his photo or has the same.

echo "--- Looking for Ankit's real photo ---\n";
$ankitPhoto = Media::where('path', 'LIKE', '%Ankit%')->orWhere('name', 'LIKE', '%Ankit%')->first();
if ($ankitPhoto) {
    echo "Found potential photo for Ankit: ID {$ankitPhoto->id} Path: {$ankitPhoto->path}\n";
    if ($ankitPhoto->id != User::find(381)->thumbnail) {
        User::find(381)->update(['thumbnail' => $ankitPhoto->id]);
        echo "Updated Ankit Salvi's thumbnail to ID {$ankitPhoto->id}\n";
    }
} else {
    echo "Could not find a photo with 'Ankit' in the name. Checking if there are other avatars.\n";
    $avatars = Media::where('path', 'LIKE', 'uploads/avatars/%')->orderBy('id', 'desc')->limit(10)->get();
    foreach ($avatars as $a) {
        echo "Avatar ID: {$a->id} Path: {$a->path}\n";
    }
}
