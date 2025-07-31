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
        BrandsRepository $brandRepo,
        OrdersRepository $orderRepo,
        ProductsRepository $productRepo,
        RolesRepository $roleRepo,
        UsersRepository $userRepo,
        MessagesRepository $messageRepo
    )
    {
        $this->info('Starting cache warmup...');
        
        $brandRepo->warmupCache();
        $categoryRepo->warmupCache();
        $orderRepo->warmupCache();
        $productRepo->warmupCache();
        $roleRepo->warmupCache();
        $userRepo->warmupCache();
        $messageRepo->warmupCache();
        
        $this->info('Cache warmup completed!');
    }
}
