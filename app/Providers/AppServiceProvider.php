<?php

namespace App\Providers;

use App\Application\Contracts\CacheInterface;
use App\Application\Contracts\PasswordHasherInterface;
use App\Application\Contracts\TelegramServiceInterface;
use App\Domain\News\Repositories\CategoryRepositoryInterface;
use App\Domain\News\Repositories\CommentRepositoryInterface;
use App\Domain\News\Repositories\NewsRepositoryInterface;
use App\Domain\News\Services\CategorySlugGeneratorInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infrastructure\Cache\LaravelCache;
use App\Infrastructure\Eloquent\Repositories\Categories\CategoryRepository;
use App\Infrastructure\Eloquent\Repositories\Categories\CategorySlugGenerator;
use App\Infrastructure\Eloquent\Repositories\Comments\CommentRepository;
use App\Infrastructure\Eloquent\Repositories\Users\UserRepository;
use App\Infrastructure\Eloquent\Repositories\News\NewsRepository;
use App\Infrastructure\Eloquent\Repositories\RefreshToken\RefreshTokenRepository;
use App\Infrastructure\Notification\Telegram\TelegramService;
use App\Infrastructure\PasswordHasher\LaravelPasswordHasher;
use App\Infrastructure\RefreshTokenHasher\Sha256RefreshTokenHasher;
use App\Policies\CategoryPolicy;
use App\Policies\NewsPolicy;
use App\Services\JwtAuth\AuthService as JwtAuthService;
use App\Services\JwtAuth\Contracts\AuthServiceInterface as JwtAuthServiceInterface;
use App\Services\OAuth\AuthService as OAuthAuthService;
use App\Services\OAuth\Contracts\AuthServiceInterface as OAuthAuthServiceInterface;
use App\Services\JwtAuth\Contracts\RefreshTokenHasherInterface;
use App\Services\JwtAuth\Contracts\RefreshTokenRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Services\JwtAuth\Contracts\UserRepositoryInterface as JwtAuthUserRepositoryInterface;
use App\Infrastructure\Eloquent\Repositories\JwtAuthUsers\UserRepository as JwtAuthUserRepository;
use App\Services\OAuth\Contracts\UserRepositoryInterface as OAuthUserRepositoryInterface;
use App\Infrastructure\Eloquent\Repositories\OAuthUsers\UserRepository as OAuthUserRepository;

use Carbon\CarbonInterval;
use Laravel\Passport\Passport;
use App\Services\OAuth\Contracts\OAuthTokenRepositoryInterface;
use App\Services\OAuth\Contracts\OAuthRefreshTokenRepositoryInterface;
use App\Infrastructure\Oauth\PassportTokenRepositoryAdapter;
use App\Infrastructure\Oauth\PassportRefreshTokenRepositoryAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
	        UserRepositoryInterface::class,
	        UserRepository::class
        );

        $this->app->bind(
            CommentRepositoryInterface::class,
            CommentRepository::class
        );

        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            NewsRepositoryInterface::class,
            NewsRepository::class
        );

        $this->app->bind(
            PasswordHasherInterface::class,
            LaravelPasswordHasher::class
        );

        $this->app->bind(
            CacheInterface::class,
            LaravelCache::class
        );

        $this->app->bind(
            TelegramServiceInterface::class,
            TelegramService::class
        );

        $this->app->bind(
            CategorySlugGeneratorInterface::class,
            CategorySlugGenerator::class
        );

        $this->app->bind(
            RefreshTokenRepositoryInterface::class,
            RefreshTokenRepository::class
        );

        $this->app->bind(
            JwtAuthUserRepositoryInterface::class,
            JwtAuthUserRepository::class
        );

        $this->app->bind(
            OAuthUserRepositoryInterface::class,
            OAuthUserRepository::class
        );

        $this->app->bind(RefreshTokenHasherInterface::class, Sha256RefreshTokenHasher::class);

        $this->app->bind(JwtAuthServiceInterface::class, JwtAuthService::class);
        $this->app->bind(OAuthAuthServiceInterface::class, OAuthAuthService::class);

        $this->app->bind(OAuthTokenRepositoryInterface::class, PassportTokenRepositoryAdapter::class);
        $this->app->bind(OAuthRefreshTokenRepositoryInterface::class, PassportRefreshTokenRepositoryAdapter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::tokensExpireIn(CarbonInterval::days(15));
        Passport::refreshTokensExpireIn(CarbonInterval::days(30));
        Passport::personalAccessTokensExpireIn(CarbonInterval::months(6));

        Paginator::useBootstrap();

        Gate::define('category.create', [CategoryPolicy::class, 'create']);
        Gate::define('category.update', [CategoryPolicy::class, 'update']);
        Gate::define('category.delete', [CategoryPolicy::class, 'delete']);

        Gate::define('news.update', [NewsPolicy::class, 'update']);
        Gate::define('news.delete', [NewsPolicy::class, 'delete']);
    }
}
