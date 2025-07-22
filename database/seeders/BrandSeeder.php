<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::factory()->create(['title' => 'Apple']);
        Brand::factory()->create(['title' => 'Samsung']);
        Brand::factory()->create(['title' => 'Xiaomi']);
        Brand::factory()->create(['title' => 'Huawei']);
        Brand::factory()->create(['title' => 'Realme']);
    }
}
