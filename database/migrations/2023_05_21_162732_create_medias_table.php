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
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('path');
            $table->string('mimetype');
            $table->string('extension');
            $table->string('url');
            $table->string('name')->nullable();
            $table->string('alt')->nullable();
            $table->text('description')->nullable();
            $table->text('height')->nullable();
            $table->text('width')->nullable();
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
        Schema::dropIfExists('medias');
    }
};
