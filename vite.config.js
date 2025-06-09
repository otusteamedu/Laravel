import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

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



            ],
            refresh: true,
        }),
    ],
});
