<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$m = \App\Models\Media::find(5708);
echo "Raw: " . $m->getRawOriginal('url') . "\n";
echo "Accessor: " . $m->url . "\n";
echo "URL Helper: " . url($m->url) . "\n";
echo "Asset Helper: " . asset($m->url) . "\n";
