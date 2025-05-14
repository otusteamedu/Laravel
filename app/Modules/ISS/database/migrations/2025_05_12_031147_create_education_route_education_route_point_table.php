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
        Schema::create('education_route_education_route_point', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_point_id');
            $table->foreign('route_point_id')->references('id')->on('education_route_points');
            $table->unsignedBigInteger('education_route_id');
            $table->foreign('education_route_id')->references('id')->on('education_routes');
            $table->timestamp('exam_date');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['route_point_id', 'education_route_id'], 'uq_route_to_point');
            $table->index('route_point_id');
            $table->index('education_route_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_route_education_route_point');
    }
};
