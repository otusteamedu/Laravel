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
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('project_id')
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('status_id')
                ->references('id')
                ->on('todo_statuses')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->text('description');
            $table->timestamp('deadline');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
