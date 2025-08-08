<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Domain\Apartment\Apartment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use App\Services\FeeCalculatorService;
use Mockery;

class ApartmentAreaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
    }

    public function test_index_returns_view_with_apartments()
    {
        Apartment::factory()->create([
            'owner' => 'John Doe',
            'serial_number' => 123,
        ]);

        Cache::flush();

        $response = $this->get('/apartments');

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('apartments');
        $this->assertEquals('ТСЖ Радуга', $response->viewData('title'));
    }

    public function test_store_creates_apartment_and_redirects()
    {
        $postData = [
            'owner' => 'Jane Smith',
            'serial_number' => 456,
        ];

        $response = $this->post('/apartments', $postData);

        $response->assertRedirect('/apartments');
        $this->assertDatabaseHas('apartments', [
            'owner' => 'Jane Smith',
            'serial_number' => 456,
        ]);
    }

    public function test_calculate_returns_correct_json()
    {
        $postData = [
            'livedQt' => 3,
            'totalArea' => 100.5,
        ];

        $response = $this->postJson('/apartment/calculate-area', $postData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['areaByNorm', 'areaOverNorm']);
    }

    public function test_calculate_fees_calls_service_and_returns_json()
    {
        $serviceMock = Mockery::mock(FeeCalculatorService::class);
        $serviceMock->shouldReceive('calculate')->once();

        $this->app->instance(FeeCalculatorService::class, $serviceMock);

        $response = $this->postJson('/apartment/calculate-fees');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Fees calculated successfully']);
    }
}
