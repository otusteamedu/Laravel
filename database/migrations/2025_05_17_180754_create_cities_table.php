<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $tableName = 'cities';

    public function up(): void
    {
        Schema::create($this->tableName, static function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->comment('Название города');
            $table->decimal('latitude', 10, 6)->comment('Широта');
            $table->decimal('longitude', 10, 6)->comment('Долгота');
            $table->tinyInteger('timezone')->comment('Часовой пояс');
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->timestamps();
            $table->comment('Города');

            $table->index(['name']);
            $table->index(['country_id', 'region_id']);
            $table->index(['latitude', 'longitude']);
        });

        $country = DB::table('countries')->select('id')->where('code', 'ru')->first();
        $region = DB::table('regions')->select('id')->first();
        $city = [
            'name' => 'Новосибирск',
            'latitude' => 55.0084,
            'longitude' => 82.9357,
            'timezone' => 7,
            'country_id' => $country->id,
            'region_id' => $region->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('cities')->insert($city);
    }

    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
