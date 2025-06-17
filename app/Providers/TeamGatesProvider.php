<?php

namespace App\Providers;

use App\Policies\TeamPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class TeamGatesProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        Gate::define('team.create', [TeamPolicy::class, 'create']);
        Gate::define('team.update', [TeamPolicy::class, 'update']);
        Gate::define('team.delete', [TeamPolicy::class, 'delete']);
    }
}
