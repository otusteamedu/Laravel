<?php

namespace Database\Factories;

use App\EloquentModels\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\EloquentModels\Video>
 */
class VideoFactory extends Factory
{
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $model = 'App\\EloquentModels\\' . $this->faker->randomElement(['Recipe']);
        $id = $this->faker->randomElement($model::pluck('id')->toArray());
        return [
            'url' => $this->faker->url(),
            'path' => $this->faker->filePath(),
            'video_type' => $model,
            'video_id' => $id,
        ];
    }
}
