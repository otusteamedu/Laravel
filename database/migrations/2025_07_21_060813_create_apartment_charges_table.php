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
        Schema::create('apartment_charges', function (Blueprint $table) {
            $table->id();
            $table->float('money_deposited');
            $table->float('fine');
            $table->float('recalculation_electricity')->nullable();
            $table->float('recalculation_heating_rub')->nullable();
            $table->float('recalculation_hot_water')->nullable();
            $table->float('recalculation_cold_water')->nullable();
            $table->float('recalculation_sewage')->nullable();
            $table->float('recalculation_solid_waste')->nullable();
            $table->float('recalculation_maintenance')->nullable();
            $table->float('balance_start')->nullable();
            $table->foreignId('apartment_id')->constrained('apartments')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartment_charges');
    }
};
