<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;


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
    public function boot()
    {
        Relation::morphMap([
            'news' => \App\Models\News::class,
            'user' => \App\Models\User::class,
            'like' => \App\Models\Like::class,
            'comment' => \App\Models\Comment::class,
            'newsPreview' => \App\Models\NewsPreview::class,
        ]);
    }
}
