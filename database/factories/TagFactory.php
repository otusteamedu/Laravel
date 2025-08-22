<?php

namespace Database\Factories;

use App\Infrastructure\EloquentModels\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\EloquentModels\Tag>
 */
class TagFactory extends Factory
{
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_en' => $this->faker->word(),
        ];
    }
}
