<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Api;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/shedule');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('shedules', \App\Http\Controllers\ShedulesController::class)->middleware('auth');

Route::post('/webhook', function () {
    $telegram = new Api('8456041854:AAGLkhZL4y_SMMX8BhlXBhRi0daPkkNPhIE');
    $result = $telegram->getWebhookUpdates();

    $text = $result['message']['text']; // Текст сообщения
    $chat = $result['message']['chat']['id']; // Уникальный идентификатор пользователя

    switch ($text) {
        case '/start':
            $message = 'Вас приветствует чат-бот школы иностранных языков Olukianova-LangSpace! Для вывода списка доступных команд введите /help';
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/teachers':
            $message = 'Английский - Новикова Н.Н.
                        Китайский - Сюэ Шень,
                        Испанский - Антонова А.А.';
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/cost':
            $message = 'Разовое занятие - 1500 рублей,
                        Абонемент на 4 занятия - 5500 рублей,
                        Абонемент на 8 занятий - 10500 рублей';
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/shedule':
            $message = 'Расписание занятий';
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/help':
            $message = 'Список доступных команд:
                        /start - начало работы с ботом
                        /teachers - преподаватели
                        /cost - информация о стоимости занятий
                        /shedule - информация о стоимости занятий
                        /help - выводит данный список';
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        default:
            $telegram->sendMessage(['chat_id' => $chat, 'text' => 'Команда не распознана']);
            break;
    }

    return response('OK', 200);
});

Route::get('/users', function () {
    dump(User::create([
        'name' => 'olga',
        'email' => 'olga@mail.ru',
        'password' => '123123123',
        'created_at' => now(),
        'updated_at' => now(),
    ]));

    dump(User::create([
        'name' => 'admin',
        'email' => 'admin@mail.ru',
        'password' => '123123123',
        'created_at' => now(),
        'updated_at' => now(),
    ]));

    return '';
});

require __DIR__.'/auth.php';
