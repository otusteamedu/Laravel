<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use SoftDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('education_route_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('route_id');
            $table->foreign('route_id')->references('id')->on('education_routes');
            $table->unsignedBigInteger('last_pass_point_id');
            $table->foreign('last_pass_point_id')
                ->references('id')->on('education_route_education_route_point');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'route_id'], 'uq_route_to_user');
            $table->index('user_id');
            $table->index('route_id');
            $table->index('last_pass_point_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_route_user');
    }
};
