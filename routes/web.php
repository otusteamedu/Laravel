<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
Сделаем очередь задач, получающих количество вакансий для заданного работодателя по его id с сайта headhunter.ru.
В файл выводятся работодатели с количеством открытых вакансий больше 500.
*/

Route::get('/job', function (
    \Illuminate\Contracts\Queue\Queue $queue,
    \Illuminate\Contracts\Bus\Dispatcher $dispatcher,
) {
    $pageCount = 1000;

    for ($i = 0; $i < $pageCount; $i++) {
        $job = new \App\Jobs\GetVacancies($i);

        dispatch($job);

    }

    return new \Illuminate\Http\Response('Задачи отправлены');

});
