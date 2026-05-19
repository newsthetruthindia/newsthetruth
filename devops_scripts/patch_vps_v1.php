<?php
$path = "/var/www/ntt/app/Http/Controllers/PublicPageController_v1.php";
$content = file_get_contents($path);

// Find the end of the map block
$search = "return \$posts->take(7);\n            });";
$replace = $search . "\n        \$data['others'] = collect(['POLITICS' => collect(), 'WORLD' => collect(), 'INDIA' => collect(), 'BENGAL' => collect()])->merge(\$data['others']);";

if (strpos($content, $search) !== false && strpos($content, "->merge(\$data['others'])") === false) {
    $content = str_replace($search, $replace, $content);
    file_put_contents($path, $content);
    echo "Fixed V1";
}
