<?php

namespace ISS\App;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use ISS\App\Presentation\Console\IssCache;
use ISS\App\Infrastructure\Events\ExamChecked\ExamChecked;
use ISS\App\Infrastructure\Events\CheckExamDates\CheckExamDates;
use ISS\App\Infrastructure\Listeners\SendStudentNotifyListener;
use ISS\App\Infrastructure\Listeners\SendTeacherMailListener;
use ISS\App\Infrastructure\Listeners\SendNotifyExamDateListener;
use ISS\App\Infrastructure\Repositories\EducationExamRepo;
use ISS\App\Infrastructure\Repositories\EducationRoutePointRepo;
use ISS\App\Infrastructure\Repositories\EducationRouteRepo;
use ISS\App\Infrastructure\Repositories\IssUserRepo;
use ISS\App\Infrastructure\Repositories\NotifyServiceRepo;
use ISS\App\Infrastructure\Repositories\EducationMaterialRepo;
use ISS\App\Infrastructure\Repositories\RealEducationRoutePointRepo;
use ISS\App\Infrastructure\Repositories\RealEducationRoutesOfUsersRepo;
use ISS\App\Infrastructure\Repositories\TeacherRepo;
use ISS\App\Application\Services\Exam\EducationExamRepoInterface;
use ISS\App\Application\Services\EducationRoute\EducationRouteRepoInterface;
use ISS\App\Application\Services\EducationRoutePoint\EducationRoutePointRepoInterface;
use ISS\App\Application\Services\IssUser\IssUserRepoInterface;
use ISS\App\Application\Services\AppServices\NotifyService\NotifyServiceRepoInterface;
use ISS\App\Application\Services\EducationMaterial\EducationMaterialRepoInterface;
use ISS\App\Application\Services\RealEducationRoutePoint\RealEducationRoutePointRepoInterface;
use ISS\App\Application\Services\RealEducationRoutesOfUsers\RealEducationRoutesOfUsersRepoInterface;
use ISS\App\Application\Services\Teacher\TeacherRepoInterface;
use ISS\App\Presentation\View\Components\IssMessages;

class IssServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $pathPrefix = base_path() . '/Modules/ISS';

        //подключаем шаблоны модуля
        $this->loadViewsFrom($pathPrefix . '/resources/views', 'iss');
        //подключаем конфиг модуля
        $this->mergeConfigFrom($pathPrefix . '/config/iss.php', 'iss');
        //подключаем локализацию
        $this->loadTranslationsFrom($pathPrefix . '/resources/lang', 'iss');
        //подключаем маршруты
        $this->loadRoutesFrom($pathPrefix . '/routes/web.php');
        $this->loadRoutesFrom($pathPrefix . '/routes/api.php');
        //подключаем миграции
        $this->loadMigrationsFrom($pathPrefix . '/database/migrations');
        //добавляем команды
        //$this->commands([/*$CommandKlass*/]);

        //связь интерфейсов для сервисов с их реализацией в классах репозиториев
        $this->app->bind(IssUserRepoInterface::class, IssUserRepo::class);
        $this->app->bind(EducationExamRepoInterface::class, EducationExamRepo::class);
        $this->app->bind(EducationRoutePointRepoInterface::class, EducationRoutePointRepo::class);
        $this->app->bind(EducationRouteRepoInterface::class, EducationRouteRepo::class);
        $this->app->bind(NotifyServiceRepoInterface::class, NotifyServiceRepo::class);
        $this->app->bind(EducationMaterialRepoInterface::class, EducationMaterialRepo::class);
        $this->app->bind(RealEducationRoutePointRepoInterface::class, RealEducationRoutePointRepo::class);
        $this->app->bind(RealEducationRoutesOfUsersRepoInterface::class, RealEducationRoutesOfUsersRepo::class);
        $this->app->bind(TeacherRepoInterface::class, TeacherRepo::class);

        //регистрация команд
        if ($this->app->runningInConsole()) {
            $this->commands([
                IssCache::class, //имя класса моей команды в пакете-модуле
            ]);
            //те для которых будет чиститься кэш при вызове optimize:clear
            /*$this->optimizes(
                optimize: 'package:optimize',
                clear: 'package:clear-optimizations',
            );*/
        }

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $pathPrefix = base_path() . '/Modules/ISS';

        //публикация конфигов
        $this->publishes(
            [
                $pathPrefix . '/config/iss.php' => config_path('iss.php'),
            ],
            'config'
        );
        //публикация стилей
        $this->publishes(
            [
                $pathPrefix . '/public/css/issMainPageStyle.css' => base_path('resources/css/iss/issMainPageStyle.css'),
                $pathPrefix . '/public/css/issUserPageStyle.css' => base_path('resources/css/iss/issUserPageStyle.css'),
                $pathPrefix . '/public/css/issNodePageStyle.css' => base_path('resources/css/iss/issNodePageStyle.css'),
                $pathPrefix . '/public/css/issSharedPageStyle.css' => base_path('resources/css/iss/issSharedPageStyle.css'),
                $pathPrefix . '/public/css/issExamCheckPageStyle.css' => base_path('resources/css/iss/issExamCheckPageStyle.css'),
                $pathPrefix . '/public/css/components/iss-messages-Style.css' => base_path('resources/css/iss/iss-messages-Style.css'),
                /** @TODO дописать сюда стили админки */
            ],
            'style'
        );
        //публикация статических файлов
        $this->publishes(
            [$pathPrefix . '/public/images' => public_path('images/iss')],
            'public'
        );

        AboutCommand::add('пакет ИОС', fn() => ['Версия' => '1.0.0']);

        //создание директив шаблонизатора
        Blade::directive(
            'issAuth',
            function () {
                return "<?php if(session()->has('issUser') && session('issUser')->issUserId): ?>";
            }
        );
        Blade::directive('endissAuth', function () {
            return "<?php endif; ?>";
        });

        Blade::directive(
            'issGuest',
            function () {
                return "<?php if(!session()->has('issUser') || is_null(session('issUser')->issUserId)): ?>";
            }
        );
        Blade::directive('endissGuest', function () {
            return "<?php endif; ?>";
        });

        //регистрация компонентов (обязательно скинуть кэш artisan optimize:clear)
        Blade::component('iss-messages', IssMessages::class);
        // или Blade::componentNamespace(IssMessages::class, 'iss-messages');

        Event::listen(ExamChecked::class, SendTeacherMailListener::class);
        Event::listen(ExamChecked::class, SendStudentNotifyListener::class);
        Event::listen(CheckExamDates::class, SendNotifyExamDateListener::class);
    }
}
