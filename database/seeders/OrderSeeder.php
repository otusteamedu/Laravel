<?php

namespace Database\Seeders;

use App\Models\OrderProduct;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        for ($i = 0; $i < 100; $i++) {
            $randomUserId = $users->random()->id;
            $order = Order::factory()->create(['user_id' => $randomUserId]);

            $count = mt_rand(1, 3);
            $randomProducts = $products->random($count);
            foreach ($randomProducts as $randomProduct) {
                $productCount = mt_rand(1, min(3, $randomProduct->stock));
                OrderProduct::factory()->create([
                    'order_id' => $order->id,
                    'product_id'=> $randomProduct->id,
                    'paid_price' => $randomProduct->price,
                    'count' => $productCount,
                ]);
            }
        }
    }
}
