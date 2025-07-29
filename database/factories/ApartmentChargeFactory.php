<?php

namespace Database\Factories;

use App\Domain\Apartment\Apartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApartmentChargeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'money_deposited' => $this->faker->randomFloat(2, 1000, 10000),
            'fine' => $this->faker->randomFloat(2, 0, 500),
            'recalculation_electricity' => $this->faker->optional(0.7)->randomFloat(2, -200, 200),
            'recalculation_heating_rub' => $this->faker->optional(0.7)->randomFloat(2, -300, 300),
            'recalculation_hot_water' => $this->faker->optional(0.7)->randomFloat(2, -150, 150),
            'recalculation_cold_water' => $this->faker->optional(0.7)->randomFloat(2, -100, 100),
            'recalculation_sewage' => $this->faker->optional(0.7)->randomFloat(2, -50, 50),
            'recalculation_solid_waste' => $this->faker->optional(0.7)->randomFloat(2, -30, 30),
            'recalculation_maintenance' => $this->faker->optional(0.7)->randomFloat(2, -200, 200),
            'balance_start' => $this->faker->optional(0.5)->randomFloat(2, -1000, 1000),
            'apartment_id' => Apartment::factory(),
        ];
    }
}