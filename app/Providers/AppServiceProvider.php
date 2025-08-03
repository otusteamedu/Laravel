<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserServiceRepository;
use App\Services\userService\UserServiceRepositoryInterface;
use Carbon\CarbonInterval;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //связь интерфейсов для сервисов с их реализацией в классах репозиториев
        $this->app->bind(UserServiceRepositoryInterface::class, UserServiceRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('isAdmin', ['App\Policy\MainAppUserGateSet', 'isAdmin']);
        Gate::define('editFio', ['App\Policy\MainAppUserGateSet', 'editFio']);

        //авторизация Passport
        Passport::tokensExpireIn(CarbonInterval::days(15));
        Passport::refreshTokensExpireIn(CarbonInterval::days(30));
        Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));

        Passport::tokensCan([
            'educationRoute:read' => 'List all education routes',
            'educationRoute:create' => 'Create route',
            //'имя области действия' => 'описание области действия',
        ]);

        Passport::defaultScopes([
            'educationRoute:read',
        ]);
    }
}
