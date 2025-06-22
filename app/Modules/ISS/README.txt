Для работы модуля нужны библиотеки:
- Bootstrap 5.3.3
- JQuery 3.7.1
- JQuery ui 1.14.1

Для установки модуля в основное приложение надо:

0) опубликовать файлы изображений из папки ISS/public/images
1) Опубликовать файлы js и библиотек из модуля (Bootstrap JQuery, JQuery ui и их css файлы)
2) добавить в bootstrap\providers.php провайдер модуля App\Modules\ISS\IssServiceProvider::class,
3) Переписать файл bootstrap\app.php основного приложения добавив туда middleware из модуля
       $middleware->alias(
                   [
                       'issAuthAdmin' => \App\Modules\ISS\src\Http\Middleware\IssAuthAdmin::class,
                       'issAuthUser' => \App\Modules\ISS\src\Http\Middleware\IssAuthUser::class
                   ]
               );
4) В файл phpunit.xml добавить
   <testsuite name="ISS">
               <directory>app/Modules/ISS/tests</directory>
           </testsuite>
5) В файл config\filesystem.php дописать
    в массив link
    //public_path('issPrivate') => base_path('app/Modules/ISS/storage/app'),--не надо
    public_path('issPublic') => base_path('app/Modules/ISS/storage/app/public'),
    в массив disks
    'iss' => [
                'driver' => 'local',
                'root' => base_path('app/Modules/ISS/storage/app'),
                'serve' => true,
                'throw' => false,
                'report' => false,
            ],
6) заполнить настройки для загрузки данных в модуль из основного приложения ISS\config\iss.php
   добавить в таблицу Users основного приложения колонки из настроек
7) Добавить в файл vite.config.js в массив input
                   //ВАЖНО!!! Vite не работает с подключаемыми библиотеками js (JQuery/Bootstrap) поэтому подключаю их
                   // и скрипты, которые их используют через публикацию файлов из модуля

                   //стили для моего шаблона layout (основное приложение)
                   //'resources/js/bootstrap-5.3.3-dist/css/bootstrap.min.css',
                   //'resources/js/plugins/jquery-ui-1.14.1.base/jquery-ui.css',
                   'resources/css/mainStyle.css',

                   //скрипты для моего шаблона layout (основное приложение)
                   //'resources/js/bootstrap-5.3.3-dist/js/bootstrap.min.js',
                   //'resources/js/jquery_3.7.1_compressed.js',
                   //'resources/js/plugins/jquery-ui-1.14.1.base/jquery-ui.js',

                   //стиль для главной страницы (основное приложение)
                   'resources/css/mainAppPageStyle.css',

                   //общие стили для ISS
                   'app/Modules/ISS/public/css/issSharedStyle.css',

                   //стиль для главной страницы Модуля ISS
                   'app/Modules/ISS/public/css/issMainPageStyle.css',

                   //стили и скрипты для страницы точки обучающего маршрута Модуля ISS
                   'app/Modules/ISS/public/css/issNodePageStyle.css', //Vite выдает ошибку на слово !important в стилях
                   //'app/Modules/ISS/public/js/issNodePage.js',

                   //стили и скрипты для страницы Пользователя Модуля ISS
                   'app/Modules/ISS/public/css/issUserPageStyle.css',
                   //'app/Modules/ISS/public/js/Chartjs4-4-9.js',
                   //'app/Modules/ISS/public/js/issUserPage.js',

                   //стили и скрипты для страницы Форма ввода результатов проверки для преподавателя
                   'app/Modules/ISS/public/css/issExamCheckPageStyle.css',

                   //стили для компонентов
                   'app/Modules/ISS/public/css/components/iss-messages-Style.css',
8) добавить в файл bootstrap\app.php
   ->withCommands([
           __DIR__.'/../app/Modules/ISS/src/Console',
       ])->

НАПОМИНАНИЯ
1) Дописать\переписать тесты для сервисов (CheckSimpleExam, ProcessExamCheck, IsExamCanBePassed, getAllManagers)
2) Дописать тесты на контроллеры
3) в сервисах где написано "//запись в лог" вместо этого в этих местах выбрасывать исключения и ловить их в контроллерах
   а уже в контроллерах писать в лог




