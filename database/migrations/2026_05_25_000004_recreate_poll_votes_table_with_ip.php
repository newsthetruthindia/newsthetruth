<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreatePollVotesTableWithIp extends Migration
{
    public function up()
    {
        Schema::dropIfExists('poll_votes');

        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poll_id');
            $table->unsignedBigInteger('poll_option_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            // Prevent double voting per IP per poll
            $table->unique(['poll_id', 'ip_address']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('poll_votes');
    }
}
