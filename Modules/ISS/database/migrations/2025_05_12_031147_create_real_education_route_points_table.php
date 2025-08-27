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
        Schema::create('real_education_route_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_point_id');
            $table->foreign('route_point_id')->references('id')->on('education_route_points');
            $table->unsignedBigInteger('route_id');
            $table->foreign('route_id')->references('id')->on('education_routes');
            $table->timestamp('exam_date');
            $table->integer('position');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['route_point_id', 'route_id'], 'uq_route_to_point');
            $table->unique(['position', 'route_id'], 'uq_route_to_position');
            $table->index('route_point_id');
            $table->index('route_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_education_route_points');
    }
};
