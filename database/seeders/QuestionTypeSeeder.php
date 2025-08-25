<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuestionType;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questionTypes = [
            [
                'id' => 1,
                'name' => 'single'
            ],
            [
                'id' => 2,
                'name' => 'multi'
            ],
            [
                'id' => 3,
                'name' => 'scale'
            ]
        ];
        QuestionType::insert($questionTypes);
        
    }
}
