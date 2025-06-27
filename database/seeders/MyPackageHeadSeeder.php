<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use My\Database\Seeders\PackageSeeder;

class MyPackageHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(PackageSeeder::class);
    }

}
