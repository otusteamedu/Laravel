<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ApartmentDetail;
use App\Models\Apartment;
use Tariff\Models\Tariff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApartmentDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_apartment_details_list()
    {
        $user = User::factory()->create();
        ApartmentDetail::factory()->count(3)->create();

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson('/api/v1/apartment-details');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_authenticated_user_can_create_apartment_detail()
    {
        $user = User::factory()->create();

        $apartment = Apartment::factory()->create();
        $tariff = Tariff::factory()->create();

        $data = [
            'registred_qt' => 5,
            'lived_qt' => 4,
            'total_area' => 75.5,
            'personal_account' => '12345',
            'account_number' => '98765',
            'apartment_id' => $apartment->id,
            'tariff_id' => $tariff->id,
        ];

        $response = $this->actingAs($user, 'sanctum')
                         ->postJson('/api/v1/apartment-details', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment($data);

        $this->assertDatabaseHas('apartment_details', $data);
    }

    public function test_authenticated_user_can_get_single_apartment_detail()
    {
        $user = User::factory()->create();
        $apartmentDetail = ApartmentDetail::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
                         ->getJson("/api/v1/apartment-details/{$apartmentDetail->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $apartmentDetail->id,
        ]);
    }

    public function test_authenticated_user_can_update_apartment_detail()
    {
        $user = User::factory()->create();
        $apartmentDetail = ApartmentDetail::factory()->create();

        $newApartment = Apartment::factory()->create();
        $newTariff = Tariff::factory()->create();

        $updateData = [
            'registred_qt' => 10,
            'lived_qt' => 8,
            'total_area' => 120.75,
            'personal_account' => '99999',
            'account_number' => '11111',
            'apartment_id' => $newApartment->id,
            'tariff_id' => $newTariff->id,
        ];

        $response = $this->actingAs($user, 'sanctum')
                         ->putJson("/api/v1/apartment-details/{$apartmentDetail->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJsonFragment($updateData);

        $this->assertDatabaseHas('apartment_details', array_merge(['id' => $apartmentDetail->id], $updateData));
    }

    public function test_authenticated_user_can_delete_apartment_detail()
    {
        $user = User::factory()->create();
        $apartmentDetail = ApartmentDetail::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
                         ->deleteJson("/api/v1/apartment-details/{$apartmentDetail->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('apartment_details', [
            'id' => $apartmentDetail->id,
        ]);
    }
}
