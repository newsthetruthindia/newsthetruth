<?php
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

echo "=== RIGOROUS REPORTER CONSOLIDATION ===\n";

$validReporters = [
    'Titas Mukherjee',
    'Ankit Salvi',
    'Tamal Saha',
    'Dipaneeta Das',
    'Staff Reporter',
    'Soonakshi Ghosh',
    'Suprav Banerjee',
    'Aniket Datta',
    'Sankha Subhra Das',
    'Ntt Desk'
];

$allPosts = App\Models\Post::all();
$updated = 0;

foreach ($allPosts as $post) {
    $name = trim($post->reporter_name);
    
    // Check if name is in valid list (case insensitive check)
    $isValid = false;
    foreach ($validReporters as $valid) {
        if (strcasecmp($name, $valid) === 0) {
            $isValid = true;
            // Standardize casing to 'Ntt Desk' if it matches
            if (strcasecmp($name, 'Ntt Desk') === 0 && $name !== 'Ntt Desk') {
                $post->reporter_name = 'Ntt Desk';
                $post->save();
                $updated++;
            }
            break;
        }
    }

    if (!$isValid || empty($name) || $name === 'NULL' || $name === 'null') {
        $post->reporter_name = 'Ntt Desk';
        $post->save();
        $updated++;
    }
}

echo "TOTAL POSTS UPDATED: $updated\n";
echo "=== CONSOLIDATION COMPLETE ===\n";
?>
