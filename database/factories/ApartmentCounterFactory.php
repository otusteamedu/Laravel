<?php

namespace Database\Factories;

use App\Models\Apartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApartmentCounterFactory extends Factory
{
    public function definition(): array
    {
        $current = $this->faker->numberBetween(100, 1000);
        $previous = $current - $this->faker->numberBetween(1, 100);

        return [
            'hot_water_previous' => $previous,
            'hot_water_current' => $current,
            'hot_water_value' => $current - $previous,
            'cold_water_previous' => $previous,
            'cold_water_current' => $current,
            'cold_water_value' => $current - $previous,
            'electricity_previous' => $previous,
            'electricity_current' => $current,
            'electricity_value' => $current - $previous,
            'wastewater_value' => $this->faker->numberBetween(1, 50),
            'apartment_id' => Apartment::factory(),
        ];
    }
}