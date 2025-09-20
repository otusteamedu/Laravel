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
        Schema::create('measure_product_recipe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('recipe_id')->constrained('recipes');
            $table->foreignId('measure_id')->constrained('measures');
            $table->string('value')->nullable();
            $table->timestamps();

            $table->index(['measure_id', 'product_id', 'recipe_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measure_product_recipe');
    }
};
