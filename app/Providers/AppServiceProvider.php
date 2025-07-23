<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;
use App\Ddd\Infrastructure\Repositories\PaymentsRepository;
use App\Ddd\Application\UseCases\Payments\Commands\Store\Handler;
use App\Repositories\OrdersRepository;
use YooKassa\Client;

class AppServiceProvider extends ServiceProvider
{
    const ADMIN_ROLE_ID = 1;
    const MANAGER_ROLE_ID = 2;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentsRepositoryInterface::class, PaymentsRepository::class);

        $this->app->bind(Client::class, function() {
            $client = new Client();
            $client->setAuth(config('custom.yookassaId'), config('custom.yookassaSecret'));
            return $client;
        });

        $this->app->bind(Handler::class, function ($app) {
            return new Handler(
                $app->make(PaymentsRepositoryInterface::class),
                $app->make(OrdersRepository::class),
                $app->make(Client::class),
                config('app.url')
            );
        });
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

        $client = new \Meilisearch\Client (env('MEILISEARCH_HOST'), env('MEILISEARCH_KEY'));
        $client->index('products')->updateFilterableAttributes([
            'category_id', 
            'price', 
            'brand_id', 
            'rating', 
            'ram', 
            'builtin_memory', 
            'screen_size'
        ]);
        $client->index('products')->updateSortableAttributes(['price']);
    }
}
