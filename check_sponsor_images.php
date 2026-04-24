<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Sponsor;

$sponsors = Sponsor::with('media')->get();
foreach ($sponsors as $sponsor) {
    echo "Sponsor: " . $sponsor->name . "\n";
    if ($sponsor->media) {
        echo "  Media ID: " . $sponsor->media->id . "\n";
        echo "  Media Path: " . $sponsor->media->path . "\n";
        echo "  Media URL (raw): " . $sponsor->media->getRawOriginal('url') . "\n";
        echo "  Media URL (accessor): " . $sponsor->media->url . "\n";
        
        $fullPath = public_path($sponsor->media->url);
        echo "  Check file at: " . $fullPath . "\n";
        echo "  Exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
    } else {
        echo "  No Media relationship found.\n";
    }
    echo "-------------------\n";
}
