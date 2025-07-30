<?php

namespace Database\Seeders;

use App\Domain\Apartment\Apartment;
use App\Models\ApartmentCounter;
use Illuminate\Database\Seeder;

class ApartmentCounterSeeder extends Seeder
{
    public function run()
    {
        // Если квартир нет — создаём 10 штук
        if (Apartment::count() === 0) {
            Apartment::factory()->count(10)->create();
        }

        // Создаём счётчики для каждой квартиры
        Apartment::each(function (Apartment $apartment) {
            ApartmentCounter::factory()->create([
                'apartment_id' => $apartment->id,
            ]);
        });
    }
}