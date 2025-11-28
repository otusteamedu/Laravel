<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::viaRequest('email', function (\Illuminate\Http\Request $request) {
            return User::where('email', $request->email)->first();
        });

        Gate::define('list-posts', function (User $user) {
            return $user->id === 1;
        });

        Gate::define('like-post', function (User $user, Post $post) {
            return $user->id !== $post->author_id;
        });
    }
}
