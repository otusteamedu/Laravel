<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProductAttributeValue;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductAsset;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $products = Product::factory()
                ->count(20)
                ->sequence(['brand_id' => 1], ['brand_id' => 2], ['brand_id' => 3], ['brand_id' => 4], ['brand_id' => 5])
                ->create(['category_id' => $category->id]);

            foreach ($products as $product) {
                ProductAsset::factory(5)->create(['product_id' => $product->id]);

                ProductAttributeValue::factory()->create([
                    'product_id' => $product->id,
                    'attribute_id' => 1,
                    'value' => fake()->randomFloat(1, 5.0, 7.0)
                ]);
                ProductAttributeValue::factory()->create([
                    'product_id' => $product->id,
                    'attribute_id' => 2,
                    'value' => fake()->randomElement([2, 4, 6, 8])
                ]);
                ProductAttributeValue::factory()->create([
                    'product_id' => $product->id,
                    'attribute_id' => 3,
                    'value' => fake()->randomElement([32, 64, 128])
                ]);
                ProductAttributeValue::factory()->create([
                    'product_id' => $product->id,
                    'attribute_id' => 4,
                    'value' => $product->brand_id == 1 ? 'iOS' : 'Android'
                ]);
            }
        }
    }
}
