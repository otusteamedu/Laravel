<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'roles';

    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('Название роли');
            $table->string('key', 100)->comment('Строковый ключ');
            $table->timestamps();
        });

        $roles = [
            [
                'name' => 'Администратор',
                'key' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Модератор',
                'key' => 'moderator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Пользователь',
                'key' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Гость',
                'key' => 'guest',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table($this->tableName)->insert($roles);

    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
