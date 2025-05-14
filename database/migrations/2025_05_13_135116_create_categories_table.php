<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->smallInteger('sort')->unsigned()->default(1);
        });

        DB::table('categories')->insert([
                                            ['name' => 'Экономика', 'slug' => 'economy',],
                                            ['name' => 'Автомобили', 'slug' => 'cars',],
                                            ['name' => 'Технологии и наука', 'slug' => 'technology_and_science',],
                                            ['name' => 'Спорт', 'slug' => 'sport',],
                                        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
