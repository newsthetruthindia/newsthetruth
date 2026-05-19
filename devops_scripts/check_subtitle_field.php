<?php
require 'd:/NTT_LOCAL_SERVER/vendor/autoload.php';
$app = require_once 'd:/NTT_LOCAL_SERVER/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\Schema;
if (Schema::hasColumn('posts', 'subtitle')) {
    echo "COLUMN_EXISTS";
} else {
    echo "COLUMN_MISSING";
}
