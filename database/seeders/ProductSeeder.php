<?php

namespace Database\Seeders;

use App\Models\Category;
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
            $products = Product::factory(20)->create(['category_id' => $category->id]);

            foreach ($products as $product) {
                ProductAsset::factory(5)->create(['product_id' => $product->id]);
            }
        }
    }
}
