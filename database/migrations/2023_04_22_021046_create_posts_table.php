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
        Schema::create('posts', function (Blueprint $table) {
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
            $table->boolean('published')->default(0)->nullable();
            $table->boolean('post_of_the_day')->default(0)->nullable();
            $table->boolean('top_post')->default(0)->nullable();
            $table->boolean('ignore_top_scheduling')->default(0)->nullable();
            $table->string('top_post_to_time')->nullable();
            $table->string('top_post_form_time')->nullable();
            $table->timestamp('post_publish_time')->nullable();
            $table->integer('primary_menu_order')->nullable();
            $table->integer('secondary_menu_order')->nullable();
            $table->integer('footer_menu_order')->nullable();
            $table->integer('post_order')->nullable();
            $table->bigInteger('gallery_id')->nullable();
            $table->string('video_url')->nullable();
            $table->enum('visibility', ['private', 'public', 'open'])->default('public')->nullable();
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
        Schema::dropIfExists('posts');
    }
};
