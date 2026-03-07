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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('role_id');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->integer('zip')->nullable();
            $table->integer('fax')->nullable();
            $table->string('alternate_email')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->text('bio')->nullable();
            $table->string('designation')->nullable();
            $table->string('salary')->nullable();
            $table->string('attachment_id')->nullable();
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
        Schema::dropIfExists('user_details');
    }
};
