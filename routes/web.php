<?php

use App\Models\Comment;
use App\Models\Todolist;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '/e'], function () {

    // Работа с задачами Todolist

    // Выведем конкретную таску с комментариями к ней
    Route::get('/todolist/{id}', function ($id) {
        $todolist = Todolist::find($id);

        if (is_null($todolist)) {
            echo 'There-s no such task. May be it has been deleted or not created ';
        } else {
            echo 'Title: '.$todolist->title.'<br>';
            echo 'Text: '.$todolist->text.'<br>';
            echo 'Deadline: '.$todolist->dedline.'<br>';
            echo 'Author: '.User::find($todolist->author_id)->name.'<br>';
            echo '<br><br><br>';
            $comments = Comment::where('todolist_id', $id)->get();
            if (! is_null($comments)) {
                foreach ($comments as $comment) {
                    echo $comment->text;
                    echo '<br>';
                    echo User::find($comment->author_id)->name.', '.$comment->created_at->format('d-m-Y');
                    echo '<br><br>';

                }
            }
        }

        return '';
    });

    // Сгенерируем группу задач
    Route::get('/create-group10', function () {
        for ($i = 0; $i < 10; $i++) {
            dump(Todolist::create([
                'title' => fake()->text(10),
                'text' => fake()->sentence(50),
                'dedline' => fake()->dateTimeBetween('0 days', '+12 months')->format('d-m-Y'),
                'author_id' => random_int(1, 5),
            ]));
        }

        return '';
    });

    // Создание задачи
    Route::get('/create', function () {
        dump(Todolist::create([
            'title' => fake()->text(10),
            'text' => fake()->sentence(50),
            'dedline' => fake()->dateTimeBetween('0 days', '+12 months')->format('d-m-Y'),
            'author_id' => random_int(1, 5),
        ]));

        return '';
    });

    // Редактирование задачи
    Route::get('/update/{id}', function ($id) {
        $todolist = Todolist::find($id);
        if (is_null($todolist)) {
            echo 'There-s no such task. May be it has been deleted or not created ';
        } else {
            $todolist->title = 'Title updated at '.date('d-m-Y');
            $todolist->save();
            dump($todolist);
        }

        return '';
    });

    Route::get('/delete/{id}', function ($id) {
        $todolist = Todolist::find($id);
        if (is_null($todolist)) {
            echo 'There-s no such task. May be it has been deleted or not created ';
        } else {
            dump($todolist->delete());

        }

        return '';
    });

    // Сгенерируем тестовые данные пользователей
    Route::get('/users', function () {

        for ($i = 0; $i < 5; $i++) {
            dump(User::create([
                'name' => fake()->name(10),
                'email' => fake()->text(10).'@mail.ru',
                'password' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        return '';
    });

    // Сгенерируем комментарии к разным таскам
    Route::get('/comments', function () {
        for ($i = 0; $i < 10; $i++) {
            dump(Comment::create([
                'todolist_id' => mt_rand(1, 5),
                'author_id' => mt_rand(1, 5),
                'text' => fake()->sentence(30),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        return '';
    });

});
