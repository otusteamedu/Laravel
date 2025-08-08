<?php

namespace Tests\Unit;

use App\Domain\Apartment\Apartment;
use App\Models\ApartmentDetail;
use App\Models\ApartmentCharge;
use App\Models\ApartmentCounter;
use App\Models\ApartmentFee;
use App\Models\Tariff;
use App\Services\FeeCalculatorService;
use Tests\TestCase;
use Mockery;

class FeeCalculatorServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Mockery::mock('alias:App\Models\ApartmentFee')
            ->shouldReceive('query->truncate')->once()
            ->shouldReceive('save')->andReturnTrue();
    }

    public function test_calculate_with_mocked_models()
    {
        $apartment = new Apartment(['id' => 1]);
        $this->partialMock(Apartment::class, function ($mock) use ($apartment) {
            $mock->shouldReceive('all')->zeroOrMoreTimes()->andReturn(collect([$apartment]));
        });

        $apartmentDetail = new ApartmentDetail([
            'apartment_id' => 1,
            'tariff_id' => 2,
            'total_area' => 50,
            'lived_qt' => 2
        ]);
        $this->partialMock(ApartmentDetail::class, function ($mock) use ($apartmentDetail) {
            $mock->shouldReceive('where')->with('apartment_id', 1)->andReturnSelf();
            $mock->shouldReceive('first')->andReturn($apartmentDetail);
        });

        $tariff = new Tariff([
            'electricity_odn' => 1,
            'cold_water_odn' => 1,
            'sewage_odn' => 1,
            'hot_water_odn' => 1,
            'lift' => 1,
            'solid_waste' => 1,
            'electricity' => 1,
            'heating' => 1,
            'heating_rub' => 1,
            'hot_water' => 1,
            'cold_water' => 1,
            'sewage' => 1,
            'multiplying_factor' => 1,
            'maintenance' => 1,
        ]);
        $this->partialMock(Tariff::class, function ($mock) use ($tariff) {
            $mock->shouldReceive('find')->with(2)->andReturn($tariff);
        });

        $counters = new ApartmentCounter([
            'electricity_value' => 1,
            'hot_water_previous' => 0,
            'hot_water_current' => 0,
            'hot_water_value' => 0,
            'cold_water_previous' => 0,
            'cold_water_current' => 0,
            'cold_water_value' => 0,
            'wastewater_value' => 0,
        ]);
        $this->partialMock(ApartmentCounter::class, function ($mock) use ($counters) {
            $mock->shouldReceive('where')->with('apartment_id', 1)->andReturnSelf();
            $mock->shouldReceive('first')->andReturn($counters);
        });

        $charges = new ApartmentCharge([
            'recalculation_sewage' => 0,
            'recalculation_electricity' => 0,
            'recalculation_cold_water' => 0,
            'recalculation_heating_rub' => 0,
            'recalculation_hot_water' => 0,
            'recalculation_solid_waste' => 0,
            'recalculation_maintenance' => 0,
            'balance_start' => 100,
            'money_deposited' => 50,
            'fine' => 10,
        ]);
        $this->partialMock(ApartmentCharge::class, function ($mock) use ($charges) {
            $mock->shouldReceive('where')->with('apartment_id', 1)->andReturnSelf();
            $mock->shouldReceive('first')->andReturn($charges);
        });

        $service = new FeeCalculatorService();
        $service->calculate();

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
