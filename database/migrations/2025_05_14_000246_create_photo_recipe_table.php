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
        Schema::create('photo_recipe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_id')->constrained('photos');
            $table->foreignId('recipe_id')->constrained('recipes');
            $table->boolean('is_preview');
            $table->timestamps();

            $table->index(['recipe_id', 'photo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_recipe');
    }
};
