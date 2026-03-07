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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname')->nullable();
            $table->boolean('is_active')->default(0)->nullable();
            $table->string('username')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->integer('pass_otp')->nullable();
            $table->dateTime('pass_otp_time')->nullable();
            $table->enum('type', ['admin', 'employee', 'user'])->default('user')->nullable();
            $table->boolean('email_verified')->default(0)->nullable();
            $table->string('v_code')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
