<?php

namespace My\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use My\PackageWithPackages\Models\Package;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PackageFactory extends Factory
{
    protected $model = Package::class; //задал свойство чтобы использовать модель с произвольным расположением

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'package_name' => fake()->unique()->word(),
            'package_content' => fake()->sentence(),
        ];
    }
}
