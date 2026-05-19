<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    if (!Schema::hasColumn('sponsors', 'media_id')) {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id')->nullable()->after('image_url');
            $table->foreign('media_id')->references('id')->on('medias')->onDelete('set null');
        });
        echo "SUCCESS: Added media_id to sponsors table\n";
    } else {
        echo "INFO: media_id column already exists in sponsors table\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
