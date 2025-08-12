<?php

namespace Database\Factories;

use App\Infrastructure\EloquentModels\Photo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\EloquentModels\Photo>
 */
class PhotoFactory extends Factory
{
    protected $model = Photo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $model = $this->faker->randomElement(['recipe', 'product']);
        $pathModel = 'App\\Infrastructure\\EloquentModels\\' . ucfirst($model);
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
