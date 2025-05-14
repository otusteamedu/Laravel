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
        Schema::create('todo_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('name');
            $table->integer('sort')->default(100);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todo_statuses');
    }
};
