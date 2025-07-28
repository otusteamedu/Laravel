<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        Setting::create([
            'month_name' => 'Январь 2023',
            'month_to_pay' => '2023-01',
            'month_to_date' => '2023-01-31',
            'bill' => 'Счёт №12345',
            'pay_up_to' => '2023-02-10',
        ]);
    }
}