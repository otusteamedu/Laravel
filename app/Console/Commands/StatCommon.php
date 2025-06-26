<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CategoriesService;
use App\Services\OrdersService;
use App\Services\ProductsService;
use App\Services\UsersService;

class StatCommon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:common';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show entities statistics';

    /**
     * Execute the console command.
     */
    public function handle(
        CategoriesService $categoriesService,
        OrdersService $ordersService,
        ProductsService $productsService,
        UsersService $usersService
    )
    {
        $categoryCount = $categoriesService->getCount();
        $productCount = $productsService->getCount();
        $orderCount = $ordersService->getCount();
        $userCount = $usersService->getCount();
        $this->line('Categories - ' . $categoryCount);
        $this->line('Products - ' . $productCount);
        $this->line('Orders - ' . $orderCount);
        $this->line('Users - ' . $userCount);
    }
}
