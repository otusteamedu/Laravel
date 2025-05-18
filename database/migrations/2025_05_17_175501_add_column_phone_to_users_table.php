<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'users';

    public function up(): void
    {
        Schema::table($this->tableName, static function (Blueprint $table) {
            $table->string('phone', 64)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table($this->tableName, static function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
