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
        Schema::table('tariffs', function (Blueprint $table) {
            $table->float('heating_rub')->nullable();
            $table->float('hot_water')->nullable();
            $table->float('hot_water_odn')->nullable();
            $table->float('cold_water')->nullable();
            $table->float('cold_water_odn')->nullable();
            $table->float('sewage')->nullable();
            $table->float('sewage_odn')->nullable();
            $table->float('solid_waste')->nullable();
            $table->float('electricity')->nullable();
            $table->float('lift')->nullable();
            $table->float('electricity_odn')->nullable();
            $table->float('multiplying_factor')->nullable()->default(1);
            $table->float('capital_repair')->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tariffs', function (Blueprint $table) {
            $table->dropColumn([
                'heating_rub',
                'hot_water',
                'hot_water_odn',
                'cold_water',
                'cold_water_odn',
                'sewage',
                'sewage_odn',
                'solid_waste',
                'electricity',
                'lift',
                'electricity_odn',
                'multiplying_factor',
            ]);
            $table->float('capital_repair')->nullable(false)->default(null)->change();
        });
    }
};
