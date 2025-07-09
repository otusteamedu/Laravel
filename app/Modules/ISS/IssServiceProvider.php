<?php


namespace App\Modules\ISS;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Modules\ISS\src\Console\IssCache;
use App\Modules\ISS\src\Events\ExamChecked\ExamChecked;
use App\Modules\ISS\src\Listeners\SendStudentNotifyListener;
use App\Modules\ISS\src\Listeners\SendTeacherMailListener;
use App\Modules\ISS\src\Repositories\EducationExamRepo;
use App\Modules\ISS\src\Repositories\EducationRoutePointRepo;
use App\Modules\ISS\src\Repositories\EducationRouteRepo;
use App\Modules\ISS\src\Repositories\IssUserRepo;
use App\Modules\ISS\src\Repositories\NotifyServiceRepo;
use App\Modules\ISS\src\Services\EducationExam\EducationExamRepoInterface;
use App\Modules\ISS\src\Services\EducationRoute\EducationRouteRepoInterface;
use App\Modules\ISS\src\Services\EducationRoutePoint\EducationRoutePointRepoInterface;
use App\Modules\ISS\src\Services\issUser\IssUserRepoInterface;
use App\Modules\ISS\src\Services\NotifyService\NotifyServiceRepoInterface;
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
        //добавляем команды
        //$this->commands([/*$CommandKlass*/]);

        //связь интерфейсов для сервисов с их реализацией в классах репозиториев
        $this->app->bind(IssUserRepoInterface::class, IssUserRepo::class);
        $this->app->bind(EducationExamRepoInterface::class, EducationExamRepo::class);
        $this->app->bind(EducationRoutePointRepoInterface::class, EducationRoutePointRepo::class);
        $this->app->bind(EducationRouteRepoInterface::class, EducationRouteRepo::class);
        $this->app->bind(NotifyServiceRepoInterface::class, NotifyServiceRepo::class);


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
                __DIR__.'/public/css/issMainPageStyle.css' => base_path('resources/css/iss/issMainPageStyle.css'),
                __DIR__.'/public/css/issUserPageStyle.css' => base_path('resources/css/iss/issUserPageStyle.css'),
                __DIR__.'/public/css/issNodePageStyle.css' => base_path('resources/css/iss/issNodePageStyle.css'),
                __DIR__.'/public/css/issSharedPageStyle.css' => base_path('resources/css/iss/issSharedPageStyle.css'),
                __DIR__.'/public/css/issExamCheckPageStyle.css' => base_path('resources/css/iss/issExamCheckPageStyle.css'),
                __DIR__.'/public/css/components/iss-messages-Style.css' => base_path('resources/css/iss/iss-messages-Style.css'),
            ],
            'style'
        );
        //публикация статических файлов
        $this->publishes(
            [__DIR__.'/public/images' => public_path('images/iss')],
            'public'
        );

        AboutCommand::add('пакет ИОС', fn () => ['Версия' => '1.0.0']);

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
        // или Blade::componentNamespace(IssMessages::class, 'iss-messages');

        Event::listen(ExamChecked::class, SendTeacherMailListener::class);
        Event::listen(ExamChecked::class, SendStudentNotifyListener::class);
    }
}
