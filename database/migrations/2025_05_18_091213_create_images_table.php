<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'images';

    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table) {
            $table->id();
            $table->string('path', 255)->comment('Путь к изображению');
            $table->boolean('main')->default(false)->comment('Основное изображение');
            $table->morphs('image');
            $table->foreignId('user_id')->comment('Id пользователя')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->comment('Изображения');

            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
