<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;

$newPermissions = [
    'publish_post',
    'review_post',
    'manage_seo',
    'manage_settings',
    'manage_menu',
    'permanent_delete'
];

echo "Adding New Missions to Database...\n";
foreach ($newPermissions as $perm) {
    Permission::firstOrCreate(['name' => $perm]);
    echo "   - Permission '$perm' is ready.\n";
}
echo "PERMISSION SEEDING COMPLETE.\n";
?>
