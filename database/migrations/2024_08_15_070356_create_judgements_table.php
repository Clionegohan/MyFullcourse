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
        Schema::create('judgements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('has_starter')->nullable()->default(false);
            $table->boolean('has_soup')->nullable()->default(false);
            $table->boolean('has_fish')->nullable()->default(false);
            $table->boolean('has_meat')->nullable()->default(false);
            $table->boolean('has_main')->nullable()->default(false);
            $table->boolean('has_salad')->nullable()->default(false);
            $table->boolean('has_dessert')->nullable()->default(false);
            $table->boolean('has_drink')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('judgements');
    }
};
