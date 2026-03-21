<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sponsors', function (Blueprint $col) {
            $col->id();
            $col->string('name');
            $col->string('image_url');
            $col->string('link_url')->nullable();
            $col->string('type')->default('banner'); // splash, banner, sidebar
            $col->boolean('is_active')->default(true);
            $col->timestamp('starts_at')->nullable();
            $col->timestamp('ends_at')->nullable();
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};
