<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TodoStatus>
 */
class TodoStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projects = Project::all();

        return [
            'project_id' => $projects->random()->id,
            'name'       => Str::ucfirst(fake()->words(2, true)),
            'sort'       => rand(10, 100),
            'color'      => fake()->randomElement(['#f8f9fa', '#198754', '#0dcaf0', '#ffc107', '#dc3545']),
        ];
    }
}
