<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Category;
use App\Models\Product;
use App\Policies\CategoryPolicy;
use App\Policies\ProductPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);

        Auth::viaRequest('custom-token', function ($request) {

            $token = $request->bearerToken();

            if($token === null){
                return null;
            }

            return User::where('api_token', $token)->first();


        });
    }
}
