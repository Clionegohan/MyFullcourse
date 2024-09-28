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
        if (!Schema::hasTable('diary_images')) {
            Schema::create('diary_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('diary_id')->constrained('diaries')->onDelete('cascade');
                $table->string('image_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diary_images');
    }
};
