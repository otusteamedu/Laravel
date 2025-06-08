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
        Schema::create('payments', function (Blueprint $table) {
            $table->id()->primary();
            $table->dateTime('payment_date');
            $table->decimal('amount')->default(0);
            $table->text('comment')->nullable();
            $table->foreignId('wallet_id')->constrained('id')->on('wallets');
            $table->foreignId('category_id')->constrained('id')->on('payment_categories');
            $table->foreignId('contragent_id')->constrained('id')->on('contragents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
