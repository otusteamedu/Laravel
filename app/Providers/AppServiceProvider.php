<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use App\Policies\ProjectPolicy;
use Illuminate\Support\Facades\URL;
use App\Policies\TodoStatusesPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        if (env('USE_HTTPS', false)) {
            URL::forceScheme('https');
        }

        Carbon::setLocale(config('app.locale'));

        View::share('appLocale', str_replace('_', '-', config('app.locale')));

        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $event->extendSocialite('yandex', \SocialiteProviders\Yandex\Provider::class);
            $event->extendSocialite('vkid', \App\SocialiteProviders\VKID\Provider::class);
        });


        Gate::define('project.invited', [ProjectPolicy::class, 'invited']);
        Gate::define('project.view', [ProjectPolicy::class, 'view']);
        Gate::define('project.create', [ProjectPolicy::class, 'create']);
        Gate::define('project.update', [ProjectPolicy::class, 'update']);
        Gate::define('project.delete', [ProjectPolicy::class, 'delete']);

        Gate::define('project.user.list', [ProjectPolicy::class, 'user_list']);
        Gate::define('project.user.manage', [ProjectPolicy::class, 'user_manage']);
        Gate::define('project.user.join', [ProjectPolicy::class, 'join']);
        Gate::define('project.user.left', [ProjectPolicy::class, 'left']);

        Gate::define('todostatuses.manage', [TodoStatusesPolicy::class, 'manage']);
    }
}
