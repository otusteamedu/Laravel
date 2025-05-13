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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('api_id')->unique();
            $table->string('name_en');
            $table->string('name_ru');
            $table->string('alternate');
            $table->foreignId('category_id')->constrained('categories')->index();
            $table->text('instruction_en');
            $table->text('instruction_ru');
            $table->foreignId('area_id')->index();
            $table->foreignId('video_id')->constrained('videos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
