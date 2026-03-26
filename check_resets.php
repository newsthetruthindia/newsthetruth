<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$resets = DB::table('password_resets')->where('email', 'newsthetruthindia@gmail.com')->orderBy('created_at', 'desc')->get();
echo "Found " . count($resets) . " reset(s) for newsthetruthindia@gmail.com\n";
foreach ($resets as $reset) {
    echo "Email: {$reset->email}, Token: {$reset->token}, Created At: {$reset->created_at}\n";
}
