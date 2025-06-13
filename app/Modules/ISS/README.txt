Для работы модуля нужны библиотеки:
- Bootstrap 5.3.3
- JQuery 3.7.1
- JQuery ui 1.14.1

Для установки модуля в основное приложение надо:

1) Опубликовать файлы js и библиотек из модуля (Bootstrap JQuery  JQuery ui и их css файлы)
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


НАПОМИНАНИЯ
1) Дописать сервисы для функционала проверки экзамена (сразу с тестами) и выгрузки учебных материалов
