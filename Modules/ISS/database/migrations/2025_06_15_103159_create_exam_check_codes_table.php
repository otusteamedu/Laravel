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
        Schema::create('exam_check_codes', function (Blueprint $table) {
            $table->id();
            $table->string('exam_check_code')->unique();
            $table->UnsignedBigInteger('iss_user_id');
            $table->foreign('iss_user_id')->references('id')->on('user_data');
            $table->UnsignedBigInteger('real_route_point_id');
            $table->foreign('real_route_point_id')->references('id')->on('real_education_route_points');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_check_codes');
    }
};
