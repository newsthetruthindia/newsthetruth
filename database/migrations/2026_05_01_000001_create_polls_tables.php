<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->boolean('is_active')->default(false)->index();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained('polls')->cascadeOnDelete();
            $table->string('option_text');
            $table->unsignedBigInteger('votes')->default(0);
            $table->timestamps();
        });

        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_option_id')->constrained('poll_options')->cascadeOnDelete();
            $table->string('voter_identifier'); // IP or session token
            $table->timestamps();
            $table->unique(['poll_option_id', 'voter_identifier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poll_votes');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('polls');
    }
};
