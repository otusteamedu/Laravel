<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\ApartmentFee;
use Illuminate\Database\Seeder;

class ApartmentFeeSeeder extends Seeder
{
    public function run()
    {
        // Если квартир нет — создаём 10
        if (Apartment::count() === 0) {
            Apartment::factory()->count(10)->create();
        }

        // Создаём по 3 записи на квартиру
        Apartment::each(function (Apartment $apartment) {
            ApartmentFee::factory()->count(3)->create([
                'apartment_id' => $apartment->id,
            ]);
        });
    }
}