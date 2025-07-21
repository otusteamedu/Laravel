<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apartment_fees', function (Blueprint $table) {
            $table->float('lift')->nullable()->after('electricity_odn');
            $table->float('maintenance_full')->nullable()->after('lift');
            $table->float('solid_waste')->nullable()->after('maintenance_full');
            $table->float('electricity')->nullable()->after('solid_waste');
            $table->float('heating')->nullable()->after('electricity');
            $table->float('heating_rub')->nullable()->after('heating');
            $table->float('hot_water')->nullable()->after('heating_rub');
            $table->float('hot_water_odn')->nullable()->after('hot_water');
            $table->float('cold_water')->nullable()->after('hot_water_odn');
            $table->float('cold_water_odn')->nullable()->after('cold_water');
            $table->float('sewage')->nullable()->after('cold_water_odn');
            $table->float('sewage_odn')->nullable()->after('sewage');
            $table->float('maintenance_total')->nullable()->after('sewage_odn');
            $table->float('accrued_expenses')->nullable()->after('maintenance_total');
            $table->float('recalculation')->nullable()->after('accrued_expenses');
            $table->float('balance_start')->nullable()->after('recalculation');
            $table->float('balance_end')->nullable()->after('balance_start');
            $table->float('paid')->nullable()->after('balance_end');
            $table->float('fine')->nullable()->after('paid');
            $table->float('total')->nullable()->after('fine');
        });
    }

    public function down(): void
    {
        Schema::table('apartment_fees', function (Blueprint $table) {
            $table->dropColumn([
                'lift',
                'maintenance_full',
                'solid_waste',
                'electricity',
                'heating',
                'heating_rub',
                'hot_water',
                'hot_water_odn',
                'cold_water',
                'cold_water_odn',
                'sewage',
                'sewage_odn',
                'maintenance_total',
                'accrued_expenses',
                'recalculation',
                'balance_start',
                'balance_end',
                'paid',
                'fine',
                'total'
            ]);
        });
    }
};