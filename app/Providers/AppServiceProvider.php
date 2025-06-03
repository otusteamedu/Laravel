<?php

namespace App\Providers;

use App\Services\WithdrawService\LogWithdrawService;
use App\Services\WithdrawService\WithdrawService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WithdrawService::class, LogWithdrawService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
