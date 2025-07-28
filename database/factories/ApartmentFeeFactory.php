<?php

namespace Database\Factories;

use App\Models\Apartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApartmentFeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'maintenance' => $this->faker->optional()->randomFloat(2, 500, 3000),
            'electricity_odn' => $this->faker->optional()->randomFloat(2, 50, 500),
            'lift' => $this->faker->optional()->randomFloat(2, 100, 800),
            'maintenance_full' => $this->faker->optional()->randomFloat(2, 1000, 5000),
            'solid_waste' => $this->faker->optional()->randomFloat(2, 200, 1000),
            'electricity' => $this->faker->optional()->randomFloat(2, 300, 2000),
            'heating' => $this->faker->optional()->randomFloat(2, 1000, 4000),
            'heating_rub' => $this->faker->optional()->randomFloat(2, 800, 3500),
            'hot_water' => $this->faker->optional()->randomFloat(2, 500, 2500),
            'hot_water_odn' => $this->faker->optional()->randomFloat(2, 100, 800),
            'cold_water' => $this->faker->optional()->randomFloat(2, 300, 1500),
            'cold_water_odn' => $this->faker->optional()->randomFloat(2, 50, 500),
            'sewage' => $this->faker->optional()->randomFloat(2, 200, 1200),
            'sewage_odn' => $this->faker->optional()->randomFloat(2, 50, 400),
            'maintenance_total' => $this->faker->optional()->randomFloat(2, 2000, 8000),
            'accrued_expenses' => $this->faker->optional()->randomFloat(2, 5000, 15000),
            'recalculation' => $this->faker->optional()->randomFloat(2, -1000, 1000),
            'balance_start' => $this->faker->optional()->randomFloat(2, -5000, 5000),
            'balance_end' => $this->faker->optional()->randomFloat(2, -5000, 5000),
            'paid' => $this->faker->optional()->randomFloat(2, 0, 10000),
            'fine' => $this->faker->optional()->randomFloat(2, 0, 2000),
            'total' => $this->faker->optional()->randomFloat(2, 5000, 20000),
            'apartment_id' => Apartment::factory(),
        ];
    }
}