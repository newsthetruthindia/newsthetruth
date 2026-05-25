<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInteractionsTables extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('parent_id')->nullable(); // For replies
            $table->text('body');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->string('type'); // e.g., 'like', 'insightful', 'surprised'
            $table->timestamps();

            $table->unique(['post_id', 'user_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reactions');
        Schema::dropIfExists('comments');
    }
}
