<?php

namespace Tests\Unit\Models;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_be_saved_with_fillable_attributes()
    {
        $data = [
            'month_name'    => 'Июль',
            'month_to_pay'  => '2025-07-31',
            'month_to_date' => '2025-07-01',
            'bill'          => '123456',
            'pay_up_to'     => '2025-08-10',
        ];

        $setting = new Setting($data);
        $setting->save();

        $this->assertDatabaseHas('settings', $data);
        $this->assertEquals('Июль', $setting->month_name);
    }
}
