<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "=== RE-ASSIGNING ARKO SAHA ARTICLES TO NTT DESK ===\n";
$affected = App\Models\Post::where('user_id', 183)->update([
    'user_id' => 0,
    'reporter_name' => 'NTT Desk'
]);

echo "Successfully re-assigned $affected articles.\n";
echo "=================================================\n";
?>
