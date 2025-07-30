<?php

namespace Database\Seeders;

use App\Domain\Apartment\Apartment;
use Illuminate\Database\Seeder;

class ApartmentSeeder extends Seeder
{
    public function run()
    {
        Apartment::factory()->count(10)->create();
    }
}