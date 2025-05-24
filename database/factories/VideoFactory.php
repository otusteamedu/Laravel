<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $model = 'App\\Models\\' . $this->faker->randomElement(['Recipe']);
        $id = $this->faker->randomElement($model::pluck('id')->toArray());
        return [
            'url' => $this->faker->url(),
            'path' => $this->faker->filePath(),
            'video_type' => $model,
            'video_id' => $id,
        ];
    }
}
