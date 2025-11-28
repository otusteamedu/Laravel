<?php

namespace App\Providers;

use App\Models\User;
use Auth;
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
    }
}
