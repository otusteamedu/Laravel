<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'categories';

    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('Название категории');
            $table->timestamps();
            $table->comment('Категории');
        });

        $categories = [
            [
                'name' => 'Электроника',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Одежда',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table($this->tableName)->insert($categories);
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
