<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuestionChartType;

class QuestionChartTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questionChartTypes = [
            [
                'id' => 1,
                'name' => 'histogram',
                'alias' => 'Гистограмма'
            ],
            [
                'id' => 2,
                'name' => 'line_scale',
                'alias' => 'Шкала'
            ],
        ];
        QuestionChartType::insert($questionChartTypes);
    }
}
