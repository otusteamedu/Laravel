<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attribute::factory()->create(['name' => 'Диагональ экрана', 'slug' => 'screen_size']);
        Attribute::factory()->create(['name' => 'Оперативная память', 'slug' => 'ram']);
        Attribute::factory()->create(['name' => 'Встроенная память', 'slug' => 'builtin_memory']);
        Attribute::factory()->create(['name' => 'Операционная система', 'slug' => 'os']);
    }
}
