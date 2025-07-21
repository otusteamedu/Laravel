<?php

namespace Database\Factories;

use App\Models\Apartment;
use App\Models\Tariff;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApartmentDetailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'registred_qt' => fake()->numberBetween(1, 5),
            'lived_qt' => fake()->numberBetween(1, 3),
            'total_area' => fake()->randomFloat(2, 40, 150),
            'personal_account' => fake()->unique()->randomNumber(8),
            'account_number' => 'ACC-' . fake()->unique()->randomNumber(6),
            'apartment_id' => Apartment::factory(),
            'tariff_id' => Tariff::factory(),
        ];
    }
}