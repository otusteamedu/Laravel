<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'currencies';

    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('Название валюты');
            $table->string('code', 3)->comment('Код');
            $table->string('char', 32)->comment('Символ');
            $table->timestamps();
            $table->comment('Валюты');
        });

        $currencies = [
            [
                'name' => 'Российский рубль',
                'code' => 'RUB',
                'char' => '₽',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Доллар США',
                'code' => 'USD',
                'char' => '$',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Евро',
                'code' => 'EUR',
                'char' => '&euro;',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table($this->tableName)->insert($currencies);
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
