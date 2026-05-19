<?php
require 'd:/NTT_LOCAL_SERVER/vendor/autoload.php';
$app = require_once 'd:/NTT_LOCAL_SERVER/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Option;

$keys = ['fb_page_id', 'fb_access_token', 'ig_account_id'];

foreach ($keys as $key) {
    $value = Option::where('key', $key)->first()?->value;
    if ($value) {
        $len = strlen($value);
        $masked = substr($value, 0, 4) . '...' . substr($value, -4);
        echo "KEY: $key | STATUS: SET | LENGTH: $len | MASKED: $masked\n";
    } else {
        echo "KEY: $key | STATUS: EMPTY\n";
    }
}
