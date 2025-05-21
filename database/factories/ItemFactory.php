<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    #[\Override]
    public function definition(): array
    {
        $users = DB::table('users')->get();
        $category = DB::table('categories')->select('id')->get();
        $country = DB::table('countries')->select('id')->where('code', 'ru')->first();
        $region = DB::table('regions')->select('id')->first();
        $city = DB::table('cities')->select('id')->first();
        $currency = DB::table('currencies')->select('id')->where('code', 'RUB')->first();

        return [
            'name' => fake()->sentence(3, 7),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(300, 10000),
            'address' => fake()->address(),
            'user_id' => $users->random()->id,
            'currency_id' => $currency->id,
            'country_id' => $country->id,
            'region_id' => $region->id,
            'city_id' => $city->id,
            'category_id' => $category->random()->id,
            'is_new' => fake()->boolean(30),
            'is_moderated' => fake()->boolean(70),
            'is_published' => fake()->boolean(70),
            'published_until' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
