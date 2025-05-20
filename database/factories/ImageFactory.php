<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Image;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    #[\Override]
    public function definition(): array
    {
        return [
            'path' => fake()->imageUrl(),
            'main' => fake()->boolean(30),
            'image_id' => Item::factory(),
            'image_type' => Item::class,
        ];
    }
}
