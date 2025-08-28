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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
            $table->string('email', 255);
            $table->string('name', 255)
                ->nullable();;
            $table->string('status', 20)
                ->default('pending')
                ->index();
            $table->decimal('total_amount', 20, 2)
                ->default(0);
            $table->text('shipping_address')
                ->nullable();
            $table->text('billing_address')
                ->nullable();
            $table->text('customer_note')
                ->nullable();
            $table->string('phone', 20)
                ->nullable();
            $table->timestamps();

            $table->index(['user_id']);
            $table->index(['email']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
