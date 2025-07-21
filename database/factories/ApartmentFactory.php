<?php

namespace Database\Factories;

use App\Models\Apartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApartmentFactory extends Factory
{
    protected $model = Apartment::class;

    public function definition()
    {
        return [
            'owner' => $this->faker->name(),
            'serial_number' => $this->faker->unique()->randomNumber(5),
        ];
    }
}