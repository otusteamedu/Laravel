<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;

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

        Paginator::useBootstrapFive();

        Blade::directive('cachedblock', function ($expression) {
            list($block, $view) = explode(',', str_replace(["'", " "], "", $expression));
            
            return "<?php 
                if (!app('cache')->has('static.{$block}')) {
                    echo app('cache')->rememberForever('static.{$block}', function () {
                        return view('{$view}')->render();
                    });
                } else {
                    echo app('cache')->get('static.{$block}');
                }
            ?>";
        });
    }
}
