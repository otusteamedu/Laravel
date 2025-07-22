<?php

namespace Tests\Unit\Models;

use App\Models\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_be_created_with_fillable_attributes()
    {
        $data = [
            'owner' => 'Иван Иванов',
            'serial_number' => 123456, // числовое поле
        ];

        $apartment = Apartment::create($data);

        $this->assertDatabaseHas('apartments', $data);
        $this->assertEquals(123456, $apartment->serial_number);
        $this->assertEquals('Иван Иванов', $apartment->owner);
    }

    public function test_to_string_returns_serial_number()
    {
        $apartment = Apartment::factory()->make([
            'serial_number' => 7890, // число
        ]);

        $this->assertEquals('7890', (string) $apartment);
    }
}
