<?php

namespace App\Console\Commands;

use App\Repositories\BrandsRepository;
use App\Repositories\CategoriesRepository;
use App\Repositories\MessagesRepository;
use App\Repositories\OrdersRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\RolesRepository;
use App\Repositories\UsersRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class WarmupCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warmup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up the application cache';

    private string $lockKey = 'lock:cache-warmup';
    private int $lockTtl = 60;

    /**
     * Execute the console command.
     */
    public function handle(
        CategoriesRepository $categoryRepo,
        BrandsRepository $brandRepo,
        OrdersRepository $orderRepo,
        ProductsRepository $productRepo,
        RolesRepository $roleRepo,
        UsersRepository $userRepo,
        MessagesRepository $messageRepo
    )
    {
        $lockAcquired = $this->acquireLock();
        
        if (!$lockAcquired) {
            $this->info('Another process is already warming cache. Skipping...');
            return;
        }

        try {
            $this->info('Starting cache clear...');
            $this->call('cache:clear');
            $this->info('Cache clear completed!');

            $this->info('Starting cache warmup...');
            
            $brandRepo->warmupCache();
            $categoryRepo->warmupCache();
            $orderRepo->warmupCache();
            $productRepo->warmupCache();
            $roleRepo->warmupCache();
            $userRepo->warmupCache();
            $messageRepo->warmupCache();
            
            $this->info('Cache warmup completed!');
        } finally {
            $this->releaseLock();
        }   
    }

    protected function acquireLock(): bool
    {
        $script = <<<'LUA'
            if redis.call("setnx", KEYS[1], ARGV[1]) == 1 then
                return redis.call("expire", KEYS[1], ARGV[2]) == 1
            else
                return false
            end
        LUA;

        $timestamp = now()->toDateTimeString();
        
        return (bool) Redis::eval(
            $script,
            1,
            $this->lockKey,
            $timestamp,
            $this->lockTtl
        );
    }

    protected function releaseLock(): void
    {
        Redis::del($this->lockKey);
    }
}
