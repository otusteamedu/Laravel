<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    const ADMIN_ROLE_ID = 1;
    const MANAGER_ROLE_ID = 2;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin-access', function ($user) {
            $roleId = $user->role_id ?? 0;
            return $roleId == self::ADMIN_ROLE_ID;
        });

        Gate::define('employee-access', function ($user) {
            $roleId = $user->role_id ?? 0;
            return $roleId == self::ADMIN_ROLE_ID || $roleId == self::MANAGER_ROLE_ID;
        });
    }
}
