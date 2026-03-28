<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            if (!Schema::hasColumn('user_details', 'twitter')) {
                $table->string('twitter')->nullable()->after('salary');
            }
            if (!Schema::hasColumn('user_details', 'facebook')) {
                $table->string('facebook')->nullable()->after('twitter');
            }
            if (!Schema::hasColumn('user_details', 'instagram')) {
                $table->string('instagram')->nullable()->after('facebook');
            }
            if (!Schema::hasColumn('user_details', 'linkedin')) {
                $table->string('linkedin')->nullable()->after('instagram');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn(['twitter', 'facebook', 'instagram', 'linkedin']);
        });
    }
};
