<?php

namespace Database\Seeders;

use App\Domain\Apartment\Apartment;
use App\Models\ApartmentDetail;
use App\Models\Tariff;
use Illuminate\Database\Seeder;

class ApartmentDetailSeeder extends Seeder
{
    public function run()
    {
        // Убедимся, что есть квартиры и тарифы
        $apartments = Apartment::all();
        $tariffs = Tariff::all();

        if ($apartments->isEmpty() || $tariffs->isEmpty()) {
            $this->call([
                ApartmentSeeder::class,
                TariffSeeder::class,
            ]);
            $apartments = Apartment::all();
            $tariffs = Tariff::all();
        }

        // Создаём детали для квартир
        foreach ($apartments as $apartment) {
            ApartmentDetail::create([
                'registred_qt' => rand(1, 5),
                'lived_qt' => rand(1, 3),
                'total_area' => rand(40, 150) + (rand(0, 99) / 100), // Пример: 87.45
                'personal_account' => rand(10000000, 99999999),
                'account_number' => 'ACC-' . rand(100000, 999999),
                'apartment_id' => $apartment->id,
                'tariff_id' => $tariffs->random()->id,
            ]);
        }
    }
}