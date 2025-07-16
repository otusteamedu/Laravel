<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\NullEngine;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->app->extend(EngineManager::class, function ($manager, $app) {
            return new class($app) extends EngineManager {
                public function engine($name = null)
                {
                    return new NullEngine;
                }
            };
        });
    }
}
