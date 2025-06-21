<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class BenchmarkCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:benchmark';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Benchmark the application cache';

    public function handle()
    {
        $this->callSilent('cache:clear');
        $this->testScenario('Without cache', function() {
            $this->callRoutes();
        });
        
        $this->callSilent('cache:warmup');
        $this->testScenario('With cache', function() {
            $this->callRoutes();
        });
    }
    
    protected function testScenario($name, $callback)
    {
        $start = microtime(true);
        $callback();
        $time = microtime(true) - $start;
        
        $this->info("{$name}: {$time} seconds");
    }
    
    protected function callRoutes()
    {
        $routes = [
            '/admin/categories',
            '/admin/products',
            '/admin/orders',
            '/admin/users',
        ];
        
        foreach ($routes as $route) {
            Http::get("http://localhost{$route}");
        }
    }
}