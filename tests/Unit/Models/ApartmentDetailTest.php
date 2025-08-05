<?php

namespace Tests\Unit\Models;

use App\Domain\Apartment\Apartment;
use App\Models\ApartmentDetail;
use Tariff\Models\Tariff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_be_created_with_fillable_attributes()
    {
        $apartment = Apartment::factory()->create();
        $tariff = Tariff::factory()->create();

        $data = [
            'registred_qt'    => 3,
            'lived_qt'        => 2,
            'total_area'      => 75.5,
            'personal_account'=> '12345',
            'account_number'  => '98765',
            'apartment_id'    => $apartment->id,
            'tariff_id'       => $tariff->id,
        ];

        $apartmentDetail = ApartmentDetail::create($data);

        $this->assertDatabaseHas('apartment_details', $data);
        $this->assertEquals($apartment->id, $apartmentDetail->apartment_id);
        $this->assertEquals($tariff->id, $apartmentDetail->tariff_id);
    }

    public function test_it_belongs_to_an_apartment()
    {
        $apartment = Apartment::factory()->create();

        $apartmentDetail = ApartmentDetail::factory()->make([
            'apartment_id' => $apartment->id,
        ]);

        $this->assertInstanceOf(Apartment::class, $apartmentDetail->apartment);
        $this->assertEquals($apartment->id, $apartmentDetail->apartment->id);
    }

    public function test_it_belongs_to_a_tariff()
    {
        $tariff = Tariff::factory()->create();

        $apartmentDetail = ApartmentDetail::factory()->make([
            'tariff_id' => $tariff->id,
        ]);

        $this->assertInstanceOf(Tariff::class, $apartmentDetail->tariff);
        $this->assertEquals($tariff->id, $apartmentDetail->tariff->id);
    }
}
