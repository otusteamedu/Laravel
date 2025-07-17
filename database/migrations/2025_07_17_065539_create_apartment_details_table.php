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
        Schema::create('apartment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('registred_qt');
            $table->integer('lived_qt');
            $table->float('total_area');
            $table->integer('personal_account');
            $table->string('account_number', 200);
            $table->foreignId('apartment_id')->constrained('apartments');
            $table->foreignId('tariff_id')->nullable()->constrained('tariffs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartment_details');
    }
};
