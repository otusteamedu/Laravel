<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tariff>
 */
class TariffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'maintenance' => $this->faker->randomFloat(2, 10, 1000),
            'heating' => $this->faker->randomFloat(2, 10, 1000),
            'heating_rub' => $this->faker->randomFloat(2, 10, 1000),
            'hot_water' => $this->faker->randomFloat(2, 10, 1000),
            'hot_water_odn' => $this->faker->randomFloat(2, 10, 1000),
            'cold_water' => $this->faker->randomFloat(2, 10, 1000),
            'cold_water_odn' => $this->faker->randomFloat(2, 10, 1000),
            'sewage' => $this->faker->randomFloat(2, 10, 1000),
            'sewage_odn' => $this->faker->randomFloat(2, 10, 1000),
            'solid_waste' => $this->faker->randomFloat(2, 10, 1000),
            'electricity' => $this->faker->randomFloat(2, 10, 1000),
            'lift' => $this->faker->randomFloat(2, 10, 1000),
            'electricity_odn' => $this->faker->randomFloat(2, 10, 1000),
            'capital_repair' => $this->faker->randomFloat(2, 0, 1000),
            'multiplying_factor' => $this->faker->randomFloat(2, 0.5, 2),
        ];
    }
}
