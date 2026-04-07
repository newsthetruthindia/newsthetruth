<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$posts = App\Models\Post::orderBy('created_at', 'desc')->take(100)->get();
$sql = "";
foreach($posts as $p) {
    if ($p->user_id && $p->user_id !== 1 && $p->reporter_name && strtolower($p->reporter_name) !== 'ntt desk') {
        $name = addslashes($p->reporter_name);
        $uid = $p->user_id;
        $id = $p->id;
        $sql .= "UPDATE posts SET user_id=$uid, reporter_name='$name' WHERE id=$id;\n";
    }
}
file_put_contents('d:\NTT_WEBSITE\fix_recent.sql', $sql);
echo "SQL generated at d:\\NTT_WEBSITE\\fix_recent.sql\n";
