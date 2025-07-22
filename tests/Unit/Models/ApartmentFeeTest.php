<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use App\Models\ApartmentFee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentFeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_be_created_with_fillable_attributes()
    {
        $apartment = Apartment::factory()->create();

        $data = [
            'maintenance'        => 100.50,
            'electricity_odn'    => 20.25,
            'lift'               => 15.00,
            'maintenance_full'   => 135.75,
            'solid_waste'        => 10.00,
            'electricity'        => 50.00,
            'heating'            => 80.00,
            'heating_rub'        => 80.00,
            'hot_water'          => 30.00,
            'hot_water_odn'      => 5.00,
            'cold_water'         => 25.00,
            'cold_water_odn'     => 4.00,
            'sewage'             => 18.00,
            'sewage_odn'         => 3.00,
            'maintenance_total'  => 145.00,
            'accrued_expenses'   => 200.00,
            'recalculation'      => 10.00,
            'balance_start'      => 500.00,
            'balance_end'        => 480.00,
            'paid'               => 200.00,
            'fine'               => 5.00,
            'total'              => 485.00,
            'apartment_id'       => $apartment->id,
        ];

        $apartmentFee = ApartmentFee::create($data);

        $this->assertDatabaseHas('apartment_fees', $data);
        $this->assertEquals($apartment->id, $apartmentFee->apartment_id);
    }

    public function test_it_belongs_to_an_apartment()
    {
        $apartment = Apartment::factory()->create();

        $apartmentFee = ApartmentFee::factory()->make([
            'apartment_id' => $apartment->id,
        ]);

        $this->assertInstanceOf(Apartment::class, $apartmentFee->apartment);
        $this->assertEquals($apartment->id, $apartmentFee->apartment->id);
    }
}
