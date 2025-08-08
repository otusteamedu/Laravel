<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ApartmentCharge;
use App\Domain\Apartment\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApartmentChargeTest extends TestCase
{
    use RefreshDatabase;

    public function test_getters_return_correct_types_and_relationship()
    {
        $apartment = Apartment::factory()->create();

        $apartmentCharge = ApartmentCharge::factory()->create([
            'apartment_id' => $apartment->id,
            'money_deposited' => 100.50,
            'fine' => 10.25,
            'recalculation_electricity' => 5.5,
            'recalculation_heating_rub' => 7.75,
            'recalculation_hot_water' => 3.33,
            'recalculation_cold_water' => 4.44,
            'recalculation_sewage' => 2.22,
            'recalculation_solid_waste' => 1.11,
            'recalculation_maintenance' => 6.66,
            'balance_start' => 50.0,
        ]);

        $this->assertEquals(100.50, $apartmentCharge->getMoneyDeposited());
        $this->assertEquals(10.25, $apartmentCharge->getFine());
        $this->assertEquals(5.5, $apartmentCharge->getRecalculationElectricity());
        $this->assertEquals(7.75, $apartmentCharge->getRecalculationHeatingRub());
        $this->assertEquals(3.33, $apartmentCharge->getRecalculationHotWater());
        $this->assertEquals(4.44, $apartmentCharge->getRecalculationColdWater());
        $this->assertEquals(2.22, $apartmentCharge->getRecalculationSewage());
        $this->assertEquals(1.11, $apartmentCharge->getRecalculationSolidWaste());
        $this->assertEquals(6.66, $apartmentCharge->getRecalculationMaintenance());
        $this->assertEquals(50.0, $apartmentCharge->getBalanceStart());
        $this->assertEquals($apartment->id, $apartmentCharge->getApartmentId());
        $this->assertTrue($apartmentCharge->apartment->is($apartment));
    }
}
