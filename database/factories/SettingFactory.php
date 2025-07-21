<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'month_name' => $this->faker->monthName(),
            'month_to_pay' => $this->faker->date('Y-m'),
            'month_to_date' => $this->faker->date('Y-m-d'),
            'bill' => 'Счёт №' . $this->faker->randomNumber(4),
            'pay_up_to' => $this->faker->date('Y-m-d'),
        ];
    }
}