<?php

// NTT Migration Fix Script
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

function syncMigration($name, $checkTable = null, $checkColumn = null) {
    echo "Checking $name... ";
    $exists = DB::table('migrations')->where('migration', $name)->exists();
    if ($exists) {
        echo "Already in migrations table.\n";
        return;
    }

    $alreadyExistsInDb = false;
    if ($checkTable && Schema::hasTable($checkTable)) {
        if ($checkColumn) {
            $alreadyExistsInDb = Schema::hasColumn($checkTable, $checkColumn);
        } else {
            $alreadyExistsInDb = true;
        }
    }

    if ($alreadyExistsInDb) {
        DB::table('migrations')->insert([
            'migration' => $name,
            'batch' => 99 // Mark as manually synced
        ]);
        echo "Marked as complete.\n";
    } else {
        echo "Not found in DB, skipping manual sync (will run via migrate).\n";
    }
}

try {
    syncMigration('2023_07_01_141620_create_citizen_journalisms_table', 'citizen_journalisms');
    syncMigration('2024_01_04_153823_create_options_table', 'options');
    syncMigration('2026_03_14_151518_create_permission_tables', 'permissions');
    syncMigration('2026_03_18_000000_add_shares_to_posts_table', 'posts', 'shares');
    syncMigration('2026_03_18_100000_create_videos_table', 'videos');
    syncMigration('2026_03_18_110000_add_type_to_videos_table', 'videos', 'type');
    syncMigration('2026_03_21_000000_create_sponsors_table', 'sponsors');
    syncMigration('2026_03_31_084300_make_role_id_nullable_on_user_details', 'user_details', 'role_id');
    syncMigration('2026_04_01_000001_add_reporter_name_to_posts_table', 'posts', 'reporter_name');
    syncMigration('2026_04_07_163146_add_media_id_to_sponsors_table', 'sponsors', 'media_id');
    syncMigration('2026_04_09_000000_add_seo_columns_to_posts_table', 'posts', 'meta_title');
    
    echo "Migration sync complete.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
