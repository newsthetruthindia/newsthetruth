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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('published_by')->nullable();
            $table->bigInteger('reviewed_by')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->string('subtitle')->nullable();
            $table->bigInteger('parent')->nullable();
            $table->text('description')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('audio_clip_url')->nullable();
            $table->bigInteger('thumbnail')->nullable();
            $table->boolean('published')->default(0);
            $table->timestamp('post_publish_time')->nullable();
            $table->integer('order')->nullable();
            $table->enum('visibility', ['private', 'public'])->default('public')->nullable();
            $table->enum('status', ['drafted', 'published'])->default('drafted')->nullable();
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
        Schema::dropIfExists('pages');
    }
};
