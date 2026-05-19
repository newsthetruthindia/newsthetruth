<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "=== INVESTIGATING ANKIT SALVI ATTRIBUTION ===\n";
$posts = App\Models\Post::where('reporter_name', 'Ankit Salvi')
    ->with('metas')
    ->limit(10)
    ->get();

foreach ($posts as $p) {
    echo "Post ID: {$p->id} | Title: {$p->title}\n";
    $credit = $p->metas->where('key', 'credit')->first()?->description;
    echo "  - Metadata Credit: " . ($credit ?: '[NONE]') . "\n";
    
    // Check for signature in body
    $body = strip_tags($p->description);
    $sig = "Not Found";
    if (preg_match('/By\s*-\s*([A-Za-z\s]{5,30})/i', $body, $matches)) {
        $sig = $matches[0];
    }
    echo "  - Body Signature: $sig\n";
    echo "----------------------------------------\n";
}
echo "=== INVESTIGATION COMPLETE ===\n";
?>
