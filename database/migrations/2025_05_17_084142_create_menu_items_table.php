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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id')->default(0)->references('id')->on('menus');
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->string('title',255);
            $table->string('class',100);
            $table->string('target',100);
            $table->string('url',255);
            $table->string('model',255);
            $table->integer('item');
            $table->tinyInteger('published')->default(1);
            $table->integer('order')->default(500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
