<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Api;

// use App\Models\Shedule;
// use Illuminate\Support\Facades\DB;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return redirect('/shedules');
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
            $message = 'Английский - Новикова Н.Н.,'.PHP_EOL.
                        'Китайский - Сюэ Шень,'.PHP_EOL.
                        'Испанский - Антонова А.А.';
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/cost':
            $message = 'Разовое занятие - 1500 рублей,'.PHP_EOL.
                        'Абонемент на 4 занятия - 5500 рублей,'.PHP_EOL.
                        'Абонемент на 8 занятий - 10500 рублей';
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/schedule':
            $message = 'Выберите способ сортировки:'.PHP_EOL.
                        '/lang - по языковым группам'.PHP_EOL.
                        '/group - по возрастным группам';
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/lang':
            $message = 'Выберите способ языковую группу:'.PHP_EOL.
                        '/en - английский'.PHP_EOL.
                        '/es - испанский'.PHP_EOL.
                        '/ch - китайский';
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/en':
            $shedules = DB::table('shedules')->where('language_code', '=', 1)->get();
            if (count($shedules) == 0) {
                $message = 'Нет ни одной записи';
            } else {
                $message = 'Все группы английского языка:'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= $shedule->group_code.' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/es':
            $shedules = DB::table('shedules')->where('language_code', '=', 2)->get();
            if (count($shedules) == 0) {
                $message = 'Нет ни одной записи';
            } else {
                $message = 'Все группы испанского языка'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= $shedule->group_code.' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/ch':
            $shedules = DB::table('shedules')->where('language_code', '=', 1)->get();
            if (count($shedules) == 0) {
                $message = 'Нет ни одной записи';
            } else {
                $message = 'Все группы китайского языка:'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= $shedule->group_code.' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;

        case '/group':
            $message = 'Выберите возрастную группу:'.PHP_EOL.
                        '/1 - младшая, 5-9 лет'.PHP_EOL.
                        '/2 - средняя, 9-12 лет'.PHP_EOL.
                        '/3 - старшая, 12-17 лет';
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;

        case '/1':
            $shedules = DB::table('shedules')->where('language_group', '=', 1)->get();
            if (count($shedules) == 0) {
                $message = 'Нет ни одной записи';
            } else {
                $message = 'Все занятия младшей группы:'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= $shedule->language_code.' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/2':
            $shedules = DB::table('shedules')->where('language_group', '=', 2)->get();
            if (count($shedules) == 0) {
                $message = 'Нет ни одной записи';
            } else {
                $message = 'Все занятия средней группы:'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= $shedule->language_code.' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/3':
            $shedules = DB::table('shedules')->where('language_group', '=', 3)->get();
            if (count($shedules) == 0) {
                $message = 'Нет ни одной записи';
            } else {
                $message = 'Все занятия средней группы:'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= $shedule->language_code.' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
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

require __DIR__.'/auth.php';
