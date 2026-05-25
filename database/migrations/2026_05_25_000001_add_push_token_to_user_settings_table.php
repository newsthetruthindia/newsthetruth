<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPushTokenToUserSettingsTable extends Migration
{
    public function up()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->string('push_token')->nullable();
        });
    }

    public function down()
    {
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn('push_token');
        });
    }
}
