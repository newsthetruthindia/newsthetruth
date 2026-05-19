cd /var/www/ntt && php -r '
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Try rendering the SponsorResource table to catch the exact error
try {
    $resource = new App\Filament\Resources\SponsorResource();
    echo "Resource class loaded OK\n";
    
    // Check if TernaryFilter exists
    if (class_exists("Filament\Tables\Filters\TernaryFilter")) {
        echo "TernaryFilter class EXISTS\n";
    } else {
        echo "TernaryFilter class MISSING\n";
    }

    // Check if the media relationship works
    $sponsor = new App\Models\Sponsor();
    echo "Sponsor model loaded OK\n";
    echo "Table: " . $sponsor->getTable() . "\n";

    // Check the actual image column - maybe disk webapp_public is the issue
    $disk = \Illuminate\Support\Facades\Storage::disk("webapp_public");
    echo "webapp_public disk OK, root: " . $disk->path("") . "\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
'
