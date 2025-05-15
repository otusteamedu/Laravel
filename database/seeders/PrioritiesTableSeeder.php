<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrioritiesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arPriorities = ['Low', 'Medium', 'High'];
        foreach ($arPriorities as $priority) {
            DB::table('priorities')->insert(
                [
                    'name' => $priority
                ]
            );
        }

    }
}
