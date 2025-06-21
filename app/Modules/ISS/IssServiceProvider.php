<?php


namespace App\Modules\ISS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Blade;
use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Repositories\IssUserRepo;
use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Repositories\EducationExamRepo;
use App\Modules\ISS\src\Services\EducationRoutePoint\EducationRoutePointRepoInterface;
use App\Modules\ISS\src\Repositories\EducationRoutePointRepo;
use App\Modules\ISS\src\Services\EducationRoute\EducationRouteRepoInterface;
use App\Modules\ISS\src\Repositories\EducationRouteRepo;
use App\Modules\ISS\src\Services\NotifyService\NotifyServiceRepoInterface;
use App\Modules\ISS\src\Repositories\NotifyServiceRepo;
use App\Modules\ISS\src\View\Components\IssMessages;


class IssServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //подключаем шаблоны модуля
        $this->loadViewsFrom(__DIR__.'/resources/views', 'iss');
        //подключаем конфиг модуля
        $this->mergeConfigFrom(__DIR__.'/config/iss.php', 'iss');
        //подключаем локализацию
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'iss');
        //подключаем маршруты
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        //подключаем миграции
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        //связь интерфейсов для сервисов с их реализацией в классах репозиториев
        $this->app->bind(IssUserRepoInterface::class, IssUserRepo::class);
        $this->app->bind(EducationExamRepoInterface::class, EducationExamRepo::class);
        $this->app->bind(EducationRoutePointRepoInterface::class, EducationRoutePointRepo::class);
        $this->app->bind(EducationRouteRepoInterface::class, EducationRouteRepo::class);
        $this->app->bind(NotifyServiceRepoInterface::class, NotifyServiceRepo::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //публикация конфигов
        $this->publishes(
            [
                __DIR__.'/config/iss.php' => config_path('iss.php'),
            ],
            'config'
        );
        //публикация стилей
        $this->publishes(
            [
                __DIR__.'/resources/css/issMainPageStyle.css' => base_path('resources/css/issMainPageStyle.css'),
                __DIR__.'/resources/css/issUserPageStyle.css' => base_path('resources/css/issUserPageStyle.css'),
                __DIR__.'/resources/css/issNodePageStyle.css' => base_path('resources/css/issNodePageStyle.css'),
            ],
            'style'
        );
        //публикация статических файлов
        $this->publishes(
            [__DIR__.'/public/images/' => public_path('images/iss')],
            'public'
        );

        AboutCommand::add('пакет ИОС', fn () => ['Версия' => '1.0.0']);
        //Blade::component('package-alert', AlertComponent::class);
        // или Blade::componentNamespace('Nightshade\\Views\\Components', 'nightshade');

        //загрузить команды
        /*if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class, //имя класса моей команды в пакете-модуле
                NetworkCommand::class, //имя класса моей команды в пакете-модуле
            ]);*/

        //создание директив шаблонизатора
        Blade::directive(
            'issAuth',
            function() { return "<?php if(session()->has('issUser') && session('issUser')->issUserId): ?>"; }
        );
        Blade::directive('endissAuth', function() { return "<?php endif; ?>"; });

        Blade::directive(
            'issGuest',
            function() { return "<?php if(!session()->has('issUser') || is_null(session('issUser')->issUserId)): ?>"; }
        );
        Blade::directive('endissGuest', function() { return "<?php endif; ?>"; });

        //регистрация компонентов (обязательно скинуть кэш artisan optimize:clear)
        Blade::component('iss-messages', IssMessages::class);
    }
}
