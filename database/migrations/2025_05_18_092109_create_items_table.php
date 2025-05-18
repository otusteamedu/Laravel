<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'items';

    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('Название товара');
            $table->text('description')->comment('Описание товара');
            $table->integer('price', unsigned: true)->comment('Цена товара');
            $table->string('address', 255)->comment('Адрес');
            $table->foreignId('user_id')->comment('ID пользователя')->constrained('users')->cascadeOnDelete();
            $table->foreignId('currency_id')->comment('ID валюты')->constrained('currencies');
            $table->foreignId('category_id')->comment('ID категории')->constrained('categories');
            $table->foreignId('country_id')->comment('ID страны')->constrained('countries');
            $table->foreignId('region_id')->comment('ID региона')->constrained('regions');
            $table->foreignId('city_id')->comment('ID города')->constrained('cities');
            $table->boolean('is_new')->comment('Новый/БУ');
            $table->boolean('is_moderated')->default(false)->comment('Прошёл модерацию');
            $table->boolean('is_published')->default(false)->comment('Опубликован');
            $table->dateTimeTz('published_until')->nullable()->comment('Опубликован до');
            $table->timestamps();
            $table->comment('Товары');

            $table->index(['price']);
            $table->index(['user_id']);
            $table->index(['category_id']);
            $table->index(['country_id']);
            $table->index(['region_id']);
            $table->index(['city_id']);
            $table->index(['is_new']);
            $table->index(['is_moderated']);
            $table->index(['is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
