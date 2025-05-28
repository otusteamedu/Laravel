<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('todo_statuses', function (Blueprint $table) {
            $table->string('color', 7)->default('#f8f8fa')->change();
        });

        $statuses = DB::table('todo_statuses')->get();

        foreach ($statuses as $status) {
            DB::table('todo_statuses')
                ->where('id', $status->id)
                ->update([
                    'color' => '#' . $status->color,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $statuses = DB::table('todo_statuses')->get();

        foreach ($statuses as $status) {
            DB::table('todo_statuses')
                ->where('id', $status->id)
                ->update([
                    'color' => ltrim($status->color, '#'),
                ]);
        }

        Schema::table('todo_statuses', function (Blueprint $table) {
            $table->string('color', 6)->default('f8f8fa')->change();
        });
    }
};
