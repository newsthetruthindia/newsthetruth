<?php
$path = "/var/www/ntt/app/Http/Controllers/PublicPageController.php";
$content = file_get_contents($path);

// Find the end of the map block
$search = "return \$groupedPosts->take(5); // Get the first 5 posts for each category\n            });";
$replace = $search . "\n        \$data['others'] = collect(['POLITICS' => collect(), 'WORLD' => collect(), 'INDIA' => collect(), 'BENGAL' => collect()])->merge(\$data['others']);";

if (strpos($content, $search) !== false && strpos($content, "->merge(\$data['others'])") === false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($path, $content);
    echo "Fixed";
}
