<?php

namespace Tests\Unit\Models;

use App\Domain\Apartment\Apartment;
use App\Models\ApartmentCounter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentCounterTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_be_created_with_fillable_attributes()
    {
        $apartment = Apartment::factory()->create();

        $data = [
            'hot_water_previous'  => 10,
            'hot_water_current'   => 20,
            'hot_water_value'     => 10,
            'cold_water_previous' => 15,
            'cold_water_current'  => 25,
            'cold_water_value'    => 10,
            'electricity_previous'=> 100,
            'electricity_current' => 150,
            'electricity_value'   => 50,
            'wastewater_value'    => 12,
            'apartment_id'        => $apartment->id,
        ];

        $apartmentCounter = ApartmentCounter::create($data);

        $this->assertDatabaseHas('apartment_counters', $data);
        $this->assertEquals($apartment->id, $apartmentCounter->apartment_id);
    }

    public function test_it_belongs_to_an_apartment()
    {
        $apartment = Apartment::factory()->create();

        $apartmentCounter = ApartmentCounter::factory()->make([
            'apartment_id' => $apartment->id,
        ]);

        $this->assertInstanceOf(Apartment::class, $apartmentCounter->apartment);
        $this->assertEquals($apartment->id, $apartmentCounter->apartment->id);
    }
}
