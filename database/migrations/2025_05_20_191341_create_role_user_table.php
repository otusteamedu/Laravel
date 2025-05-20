<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'role_user';

    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->comment('Идентификатор пользователя')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('role_id')
                ->comment('Идентификатор роли')
                ->constrained('roles')
                ->cascadeOnDelete();
            $table->comment('Pivot таблица связи пользователей и их ролей');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
