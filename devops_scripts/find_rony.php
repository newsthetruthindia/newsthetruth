<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

$u = App\Models\User::where('firstname', 'Rony')->first();
if ($u) {
    echo "ID:" . $u->id . " NAME:" . $u->firstname . " " . $u->lastname . "\n";
} else {
    echo "Rony not found.\n";
}
