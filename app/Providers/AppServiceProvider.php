<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Infrastructure\Eloquent\Repositories\UserRepository;
use App\Services\Repositories\TodoStatusRepositoryInterface;
use App\Services\Repositories\ProjectUserRepositoryInterface;
use App\Infrastructure\Eloquent\Repositories\ProjectRepository;
use App\Services\Repositories\UserSocialeteRepositoryInterface;
use App\Infrastructure\Eloquent\Repositories\TodoStatusRepository;
use App\Infrastructure\Eloquent\Repositories\ProjectUserRepository;
use App\Infrastructure\Eloquent\Repositories\UserSocialeteRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserSocialeteRepositoryInterface::class, UserSocialeteRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(ProjectUserRepositoryInterface::class, ProjectUserRepository::class);
        $this->app->bind(TodoStatusRepositoryInterface::class, TodoStatusRepository::class);
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

        Carbon::setLocale(config('app.locale'));

        View::share('appLocale', str_replace('_', '-', config('app.locale')));
    }
}
