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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->bigInteger('user_id');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('thumbnail')->nullable();
            $table->integer('primary_menu_order')->nullable();
            $table->integer('secondary_menu_order')->nullable();
            $table->integer('footer_menu_order')->nullable();
            $table->bigInteger('gallery_id')->nullable();
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
        Schema::dropIfExists('categories');
    }
};
