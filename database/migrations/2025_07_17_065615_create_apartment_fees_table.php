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
        Schema::create('apartment_fees', function (Blueprint $table) {
            $table->id();
            $table->float('maintenance')->nullable();
            $table->float('electricity_odn')->nullable();
            // ... все поля из 0001_initial.py
            $table->foreignId('apartment_id')->constrained('apartments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartment_fees');
    }
};
