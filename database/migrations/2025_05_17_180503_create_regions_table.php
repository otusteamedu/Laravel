<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'regions';

    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('Название региона');
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->timestamps();
            $table->comment('Регионы');

            $table->index(['country_id']);
        });

        $country = DB::table('countries')->select('id')->where('code', 'ru')->first();
        $region = [
            'name' => 'Новосибирская область',
            'country_id' => $country->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('regions')->insert($region);
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
