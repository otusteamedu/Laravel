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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('alias', 150)->unique();
            $table->text('text')->nullable();
            $table->string('image')->nullable();
            $table->text('images')->nullable();
            $table->tinyInteger('is_sale')->default(0);
            $table->tinyInteger('published')->default(1);
            $table->integer('order')->default(500);
            $table->float('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
