<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apartment_details', function (Blueprint $table) {
            $table->foreignId('tariff_id')->nullable()->constrained('tariffs');
        });
    }

    public function down(): void
    {
        Schema::table('apartment_details', function (Blueprint $table) {
            $table->dropForeign(['tariff_id']);
            $table->dropColumn('tariff_id');
        });
    }
};

