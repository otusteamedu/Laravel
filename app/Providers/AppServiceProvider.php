<?php

namespace App\Providers;

use App\Infrastructure\Eloquent\Repositories\UserRepository;
use App\Infrastructure\Eloquent\Repositories\UserSocialeteRepository;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\Repositories\UserSocialeteRepositoryInterface;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserSocialeteRepositoryInterface::class, UserSocialeteRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('USE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('yandex', \SocialiteProviders\Yandex\Provider::class);
            $event->extendSocialite('vkid', \App\SocialiteProviders\VKID\Provider::class);
        });

        View::share('appLocale', str_replace('_', '-', $this->app->getLocale()));
    }
}
