<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('blogs.update', function (User $user, Blog $blog) {
            return $user->id === $blog->author_id;
        });

        Auth::viaRequest('custom-token', function (Request $request) {
            $token = $request->route()->action['parameters']['token'];

            if ($token == null) {
                return null;
            }

            return User::where('api_token', $token)->first();
        });

    }
}
