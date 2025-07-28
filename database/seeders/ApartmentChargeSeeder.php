<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\ApartmentCharge;
use Illuminate\Database\Seeder;

class ApartmentChargeSeeder extends Seeder
{
    public function run()
    {
        // Если квартир нет — создаём 10 штук
        if (Apartment::count() === 0) {
            Apartment::factory()->count(10)->create();
        }

        // Создаём начисления для каждой квартиры
        Apartment::each(function (Apartment $apartment) {
            ApartmentCharge::factory()->count(2)->create([ // По 2 записи на квартиру
                'apartment_id' => $apartment->id,
            ]);
        });
    }
}