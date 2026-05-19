<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $output) {
            $output->id();
            $output->foreignId('post_id')->constrained()->onDelete('cascade');
            $output->foreignId('user_id')->constrained()->onDelete('cascade');
            $output->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            $output->text('content');
            $output->enum('status', ['pending', 'approved', 'spam', 'hidden'])->default('approved');
            $output->boolean('is_pinned')->default(false);
            $output->ipAddress('ip_address')->nullable();
            $output->timestamps();
            $output->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
