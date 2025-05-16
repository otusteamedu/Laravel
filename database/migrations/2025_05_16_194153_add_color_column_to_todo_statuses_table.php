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
        Schema::table('todo_statuses', function (Blueprint $table) {
            $table->string('color', 6)->default('f8f8fa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todo_statuses', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
