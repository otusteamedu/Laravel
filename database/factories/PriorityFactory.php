<?php

namespace Database\Factories;

use App\Models\Priority;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriorityFactory extends Factory
{

    protected $model = Priority::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Low', 'Medium', 'High', 'Critical']),
        ];
    }
}
