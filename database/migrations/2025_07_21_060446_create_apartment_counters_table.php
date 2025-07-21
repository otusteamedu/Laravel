<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('apartment_counters', function (Blueprint $table) {
            $table->id();
            $table->integer('hot_water_previous')->default(0);
            $table->integer('hot_water_current')->default(0);
            $table->integer('hot_water_value')->default(0);
            $table->integer('cold_water_previous')->default(0);
            $table->integer('cold_water_current')->default(0);
            $table->integer('cold_water_value')->default(0);
            $table->integer('electricity_previous')->default(0);
            $table->integer('electricity_current')->default(0);
            $table->integer('electricity_value')->default(0);
            $table->integer('wastewater_value')->default(0);
            $table->foreignId('apartment_id')->constrained('apartments')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartment_counters');
    }
};
