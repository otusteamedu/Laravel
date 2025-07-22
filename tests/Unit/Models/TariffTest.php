<?php

namespace Tests\Unit\Models;

use App\Models\Tariff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TariffTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_be_saved_with_fillable_attributes_and_capitalizes_name()
    {
        $data = [
            'name'               => 'example tariff',
            'maintenance'        => 100,
            'heating'            => 50,
            'heating_rub'        => 20,
            'hot_water'          => 30,
            'hot_water_odn'      => 10,
            'cold_water'         => 25,
            'cold_water_odn'     => 5,
            'sewage'             => 15,
            'sewage_odn'         => 3,
            'solid_waste'        => 12,
            'electricity'        => 40,
            'lift'               => 7,
            'electricity_odn'    => 8,
            'capital_repair'     => 18,
            'multiplying_factor' => 1.5,
        ];

        $tariff = new Tariff($data);
        $tariff->save();

        $this->assertDatabaseHas('tariffs', [
            'name' => 'Example tariff'  // Проверяем капитализацию
        ]);

        $this->assertEquals('Example tariff', (string) $tariff);
    }
}
