<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(){
        $width=320;
        $height=250;
        $name = fake()->name;
        return [
            'name' => $name,
            'preview'=> fake()->sentence,
            'text' => fake()->paragraph,
            'link'=> Str::slug($name),
            'user_id'=>User::factory(),
            'photo'=> fake()->imageUrl($width, $height),
            'create_at' => fake()->dateTimeBetween('-1 year', 'now')
        ];
    }
}
