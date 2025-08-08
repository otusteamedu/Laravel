<?php

namespace Tests\Feature\Domain\Apartment;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domain\Apartment\Apartment;
use App\Domain\Apartment\ValueObjects\Owner;
use App\Domain\Apartment\ValueObjects\SerialNumber;
use App\Models\ApartmentDetail;
use App\Models\ApartmentFee;

class ApartmentFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_apartment_and_relations()
    {
        $owner = new Owner('Иван Иванов');
        $serialNumber = new SerialNumber(123);

        $apartment = Apartment::create($owner, $serialNumber);
        $apartment->save();

        $detail = ApartmentDetail::factory()->create(['apartment_id' => $apartment->id]);
        $fee = ApartmentFee::factory()->create(['apartment_id' => $apartment->id]);

        $this->assertEquals($owner->toString(), $apartment->getOwner()->toString());
        $this->assertEquals($serialNumber->toInt(), $apartment->getSerialNumber()->toInt());

        $this->assertTrue($apartment->details->contains($detail));
        $this->assertTrue($apartment->fees->contains($fee));
    }
}
