<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use App\Contracts\CustomAuthViewResponse;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Contracts\AuthorizationViewResponse;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthorizationViewResponse::class, CustomAuthViewResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::tokensExpireIn(CarbonInterval::days(15));
        Passport::refreshTokensExpireIn(CarbonInterval::days(30));
        Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));

        Passport::tokensCan([
            'user:read' => 'Retrieve the user info',
            'cars:create' => 'Cars create',
            'cars:update' => 'Cars update',
        ]);

        Passport::defaultScopes([
            'user:read',
        ]);
    }
}
