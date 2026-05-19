cd /var/www/ntt && php -r '
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Try to query sponsors to reproduce the error
try {
    $sponsors = App\Models\Sponsor::all();
    echo "Sponsors OK: " . count($sponsors) . " records\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
'
