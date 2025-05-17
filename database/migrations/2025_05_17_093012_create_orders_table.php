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
            $table->unsignedBigInteger('basket_id');
            $table->unsignedBigInteger('payment_id');
            $table->unsignedBigInteger('delivery_id');
            $table->float('delivery_price');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('middlename')->nullable();
            $table->string('surname')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('basket_id')
                ->references('id')
                ->on('baskets');
                //->onDelete('cascade');

            $table->foreign('payment_id')
                ->references('id')
                ->on('payments');
                //->onDelete('cascade');

            $table->foreign('delivery_id')
                ->references('id')
                ->on('deliveries');
                //->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
                //->onDelete('cascade');

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
