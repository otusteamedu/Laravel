<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Domain\Apartment\Apartment;
use App\Models\ApartmentCharge;
use App\Models\ApartmentCounter;
use App\Models\ApartmentDetail;
use App\Models\ApartmentFee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WarmupCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_warmup_cache_for_all_entities_when_no_entity_argument()
    {
        $apartment = Apartment::factory()->create();
        $charge = ApartmentCharge::factory()->create();
        $counter = ApartmentCounter::factory()->create();
        $detail = ApartmentDetail::factory()->create();
        $fee = ApartmentFee::factory()->create();

        $this->assertEmpty(Cache::get("apartments_{$apartment->id}"));
        $this->assertEmpty(Cache::get("apartment_charges_{$charge->id}"));
        $this->assertEmpty(Cache::get("apartment_counters_{$counter->id}"));
        $this->assertEmpty(Cache::get("apartment_details_{$detail->id}"));
        $this->assertEmpty(Cache::get("apartment_fees_{$fee->id}"));

        $exitCode = Artisan::call('warmup:cache');

        $this->assertEquals(0, $exitCode);

        $this->assertNotEmpty(Cache::get("apartments_{$apartment->id}"));
        $this->assertNotEmpty(Cache::get("apartment_charges_{$charge->id}"));
        $this->assertNotEmpty(Cache::get("apartment_counters_{$counter->id}"));
        $this->assertNotEmpty(Cache::get("apartment_details_{$detail->id}"));
        $this->assertNotEmpty(Cache::get("apartment_fees_{$fee->id}"));
    }

    public function test_command_fails_on_unknown_entity()
    {
        $exitCode = Artisan::call('warmup:cache', ['entity' => 'unknown_entity']);
        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unknown entity: unknown_entity', Artisan::output());
    }

    public function test_command_clears_cache_before_warming()
    {
        $apartment = Apartment::factory()->create();
        Cache::put("apartments_{$apartment->id}", ['foo' => 'bar'], 3600);
        $this->assertNotEmpty(Cache::get("apartments_{$apartment->id}"));

        $exitCode = Artisan::call('warmup:cache', [
            'entity' => 'apartments',
            '--clear' => true,
        ]);

        $this->assertEquals(0, $exitCode);

        $cached = Cache::get("apartments_{$apartment->id}");
        $this->assertNotEmpty($cached);
        $this->assertEquals($apartment->id, $cached['id']);
    }

    public function test_command_limits_number_of_records()
    {
        $apartments = Apartment::factory()->count(5)->create();

        $exitCode = Artisan::call('warmup:cache', [
            'entity' => 'apartments',
            '--limit' => 3,
        ]);

        $this->assertEquals(0, $exitCode);

        foreach ($apartments->take(3) as $apartment) {
            $this->assertNotEmpty(Cache::get("apartments_{$apartment->id}"));
        }

        foreach ($apartments->slice(3) as $apartment) {
            $this->assertEmpty(Cache::get("apartments_{$apartment->id}"));
        }
    }
}
