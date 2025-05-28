<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductAsset>
 */
class ProductAssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->optional(0.5, 'image')->randomElement(['image', 'video']);
        $url = $type == 'image' ? 'uploads/placeholder.jpg' : 'uploads/placeholder.mp4';

        return [
            'product_id' => 1,
            'type' => $type,
            'asset_url' => $url,
            'created_at' => now(),
        ];
    }
}
