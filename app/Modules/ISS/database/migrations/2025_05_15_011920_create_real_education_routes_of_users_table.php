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
        Schema::create('real_education_routes_of_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_data_id');
            $table->foreign('user_data_id')->references('id')->on('user_data');
            $table->unsignedBigInteger('route_id');
            $table->foreign('route_id')->references('id')->on('education_routes');
            $table->unsignedBigInteger('last_pass_point_id')->nullable();
            $table->foreign('last_pass_point_id')
                ->references('id')->on('real_education_route_points');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_data_id', 'route_id'], 'uq_route_to_user');
            $table->index('user_data_id');
            $table->index('route_id');
            $table->index('last_pass_point_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_education_routes_of_users');
    }
};
