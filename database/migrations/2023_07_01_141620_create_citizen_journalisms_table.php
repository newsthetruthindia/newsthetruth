<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizen_journalisms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('title');
            $table->dateTime('datetime');
            $table->string('subtitle')->nullable();
            $table->text('description');
            $table->string('attachment_url')->nullable();
            $table->bigInteger('accept_by')->nullable();
            $table->boolean('posted')->nullable();
            $table->string('place');
            $table->string('credit')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citizen_journalisms');
    }
};
