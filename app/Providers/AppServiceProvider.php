<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use App\Policies\ProjectPolicy;
use App\Policies\ProjectUserPolicy;
use Illuminate\Support\Facades\URL;
use App\Policies\TodoStatusesPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Infrastructure\Eloquent\Repositories\UserRepository;
use App\Services\Repositories\TodoStatusRepositoryInterface;
use App\Services\Repositories\ProjectUserRepositoryInterface;
use App\Services\Repositories\UserProfileRepositoryInterface;
use App\Infrastructure\Eloquent\Repositories\ProjectRepository;
use App\Services\Repositories\UserSocialeteRepositoryInterface;
use App\Infrastructure\Eloquent\Repositories\TodoStatusRepository;
use App\Infrastructure\Eloquent\Repositories\ProjectUserRepository;
use App\Infrastructure\Eloquent\Repositories\UserProfileRepository;
use App\Infrastructure\Eloquent\Repositories\UserSocialeteRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(ProjectUserRepositoryInterface::class, ProjectUserRepository::class);
        $this->app->bind(TodoStatusRepositoryInterface::class, TodoStatusRepository::class);
        $this->app->bind(UserProfileRepositoryInterface::class, UserProfileRepository::class);
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

        Carbon::setLocale(config('app.locale'));

        View::share('appLocale', str_replace('_', '-', config('app.locale')));

        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('yandex', \SocialiteProviders\Yandex\Provider::class);
            $event->extendSocialite('vkid', \App\SocialiteProviders\VKID\Provider::class);
        });


        Gate::define('project.view', [ProjectPolicy::class, 'view']);
        Gate::define('project.create', [ProjectPolicy::class, 'create']);
        Gate::define('project.update', [ProjectPolicy::class, 'update']);
        Gate::define('project.delete', [ProjectPolicy::class, 'delete']);

        Gate::define('project.user.list', [ProjectUserPolicy::class, 'list']);
        Gate::define('project.user.view', [ProjectUserPolicy::class, 'view']);
        Gate::define('project.user.manage', [ProjectUserPolicy::class, 'manage']);

        Gate::define('todostatuses.list', [TodoStatusesPolicy::class, 'list']);
        Gate::define('todostatuses.manage', [TodoStatusesPolicy::class, 'manage']);
    }
}
