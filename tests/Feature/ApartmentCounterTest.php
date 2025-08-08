<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ApartmentCounter;
use App\Domain\Apartment\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApartmentCounterTest extends TestCase
{
    use RefreshDatabase;

    public function test_getters_return_correct_types_and_relationship()
    {
        $apartment = Apartment::factory()->create();

        $apartmentCounter = ApartmentCounter::factory()->create([
            'hot_water_previous' => 10,
            'hot_water_current' => 15,
            'hot_water_value' => 5,
            'cold_water_previous' => 20,
            'cold_water_current' => 25,
            'cold_water_value' => 5,
            'electricity_previous' => 100,
            'electricity_current' => 150,
            'electricity_value' => 50,
            'wastewater_value' => 7,
            'apartment_id' => $apartment->id,
        ]);

        $this->assertIsFloat($apartmentCounter->getHotWaterPrevious());
        $this->assertIsFloat($apartmentCounter->getHotWaterCurrent());
        $this->assertIsFloat($apartmentCounter->getHotWaterValue());
        $this->assertIsFloat($apartmentCounter->getColdWaterPrevious());
        $this->assertIsFloat($apartmentCounter->getColdWaterCurrent());
        $this->assertIsFloat($apartmentCounter->getColdWaterValue());
        $this->assertIsFloat($apartmentCounter->getElectricityPrevious());
        $this->assertIsFloat($apartmentCounter->getElectricityCurrent());
        $this->assertIsFloat($apartmentCounter->getElectricityValue());
        $this->assertIsFloat($apartmentCounter->getWastewaterValue());
        $this->assertIsInt($apartmentCounter->getApartmentId());

        $this->assertTrue($apartmentCounter->apartment()->exists());
        $this->assertEquals($apartment->id, $apartmentCounter->getApartment()->id);
    }
}
