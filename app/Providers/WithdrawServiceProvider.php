<?php

namespace App\Providers;

use App\Services\WithdrawService;
use App\Services\WithdrawServiceImpl;
use Illuminate\Support\ServiceProvider;

class WithdrawServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(
            WithdrawService::class,
            WithdrawServiceImpl::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
