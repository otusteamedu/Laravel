<?php

namespace Tests\Unit\Models;

use App\Domain\Apartment\Apartment;
use App\Models\ApartmentCharge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentChargeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_be_created_with_fillable_attributes()
    {
        $apartment = Apartment::factory()->create();

        $data = [
            'money_deposited'          => 1000.50,
            'fine'                     => 50.00,
            'recalculation_electricity'=> 10.00,
            'recalculation_heating_rub'=> 20.00,
            'recalculation_hot_water'  => 30.00,
            'recalculation_cold_water' => 40.00,
            'recalculation_sewage'     => 5.00,
            'recalculation_solid_waste'=> 15.00,
            'recalculation_maintenance'=> 25.00,
            'balance_start'            => 500.00,
            'apartment_id'             => $apartment->id,
        ];

        $apartmentCharge = ApartmentCharge::create($data);

        $this->assertDatabaseHas('apartment_charges', $data);
        $this->assertEquals($apartment->id, $apartmentCharge->apartment_id);
    }

    public function test_it_belongs_to_an_apartment()
    {
        $apartment = Apartment::factory()->create();

        $apartmentCharge = ApartmentCharge::factory()->make([
            'apartment_id' => $apartment->id,
        ]);

        $this->assertInstanceOf(Apartment::class, $apartmentCharge->apartment);
        $this->assertEquals($apartment->id, $apartmentCharge->apartment->id);
    }
}
