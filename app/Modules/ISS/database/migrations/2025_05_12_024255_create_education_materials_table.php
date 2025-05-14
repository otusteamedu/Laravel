<?php

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use softDeletes;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('education_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_type_id');
            $table->foreign('material_type_id')->references('id')->on('education_material_types');
            $table->string('file_path', 500)->unique();
            $table->unsignedBigInteger('point_id');
            $table->foreign('point_id')->references('id')->on('education_route_points');
            $table->timestamps();
            $table->softDeletes();

            $table->index('material_type_id');
            $table->index('point_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_materials');
    }
};
