<?php

namespace App\Console\Commands;

use App\Repositories\CategoriesRepository;
use App\Repositories\OrdersRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\RolesRepository;
use App\Repositories\UsersRepository;
use Illuminate\Console\Command;

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

    /**
     * Execute the console command.
     */
    public function handle(
        CategoriesRepository $categoryRepo,
        OrdersRepository $orderRepo,
        ProductsRepository $productRepo,
        RolesRepository $roleRepo,
        UsersRepository $userRepo
    )
    {
        $this->info('Starting cache warmup...');
        
        $categoryRepo->warmupCache();
        $orderRepo->warmupCache();
        $productRepo->warmupCache();
        $roleRepo->warmupCache();
        $userRepo->warmupCache();
        
        $this->info('Cache warmup completed!');
    }
}
