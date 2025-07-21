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
                $table->float('heating_rub')->nullable()->after('heating');
                $table->float('hot_water')->nullable()->after('heating_rub');
                $table->float('hot_water_odn')->nullable()->after('hot_water');
                $table->float('cold_water')->nullable()->after('hot_water_odn');
                $table->float('cold_water_odn')->nullable()->after('cold_water');
                $table->float('sewage')->nullable()->after('cold_water_odn');
                $table->float('sewage_odn')->nullable()->after('sewage');
                $table->float('solid_waste')->nullable()->after('sewage_odn');
                $table->float('electricity')->nullable()->after('solid_waste');
                $table->float('lift')->nullable()->after('electricity');
                $table->float('electricity_odn')->nullable()->after('lift');
                $table->float('multiplying_factor')->nullable()->default(1)->after('capital_repair');
                
                $table->float('capital_repair')->nullable()->default(0)->change();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tariffs', function (Blueprint $table) {
            //
        });
    }
};
