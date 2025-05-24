<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $model = $this->faker->randomElement(['recipe', 'product']);
        $pathModel = 'App\\Models\\' . ucfirst($model);
        $id = $this->faker->randomElement($pathModel::pluck('id')->toArray());
        return [
            'url' => $this->faker->url(),
            'path' => $this->faker->filePath(),
            'is_preview' => $this->faker->boolean(),
            'photo_type' => $model,
            'photo_id' => $id,
        ];
    }
}
