<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'countries';

    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('Название страны');
            $table->string('code', 2)->unique();
            $table->timestamps();
            $table->comment('Страны');

            $table->index(['code']);
        });

        $country = [
            'name' => 'Россия',
            'code' => 'ru',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('countries')->insert($country);

    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
