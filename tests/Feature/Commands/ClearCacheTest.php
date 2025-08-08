<?php

namespace Tests\Feature\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ClearCacheTest extends TestCase
{
    public function test_clears_cache_for_specific_entity()
    {
        Cache::shouldReceive('tags->flush')->once()->withNoArgs();
        $exitCode = Artisan::call('clear:cache', ['entity' => 'apartments']);
        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Cache cleared for apartments', Artisan::output());
    }

    public function test_clears_cache_for_all_entities_when_no_argument_given()
    {
        Cache::shouldReceive('tags->flush')->times(5)->withNoArgs();
        $exitCode = Artisan::call('clear:cache');
        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('No entity specified. Clearing cache for all entities.', Artisan::output());
    }

    public function test_fails_when_entity_is_unknown()
    {
        $exitCode = Artisan::call('clear:cache', ['entity' => 'unknown_entity']);
        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('Unknown entity: unknown_entity', Artisan::output());
    }
}
