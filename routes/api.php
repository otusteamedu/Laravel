<?php

use App\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Api;

// use App\Models\Shedule;
// use Illuminate\Support\Facades\DB;

Route::post('/webhook', function () {
    $telegram = new Api(Config::TELEGRAM_API_TOKEN);
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
                        'Испанский - Мерседес Гарсиа';
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
                        '/en - '.Config::LANG[1].PHP_EOL.
                        '/es - '.Config::LANG[2].PHP_EOL.
                        '/ch - '.Config::LANG[3].PHP_EOL;
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/en':
            $shedules = DB::table('shedules')->where('language_code', '=', 1)->get();
            if (count($shedules) == 0) {
                $message = Config::NO_GROUP;
            } else {
                $message = 'Все группы английского языка:'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= Config::AGE[$shedule->group_code].' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/es':
            $shedules = DB::table('shedules')->where('language_code', '=', 2)->get();
            if (count($shedules) == 0) {
                $message = Config::NO_GROUP;
            } else {
                $message = 'Все группы испанского языка'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= Config::AGE[$shedule->group_code].' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/ch':
            $shedules = DB::table('shedules')->where('language_code', '=', 3)->get();
            if (count($shedules) == 0) {
                $message = Config::NO_GROUP;
            } else {
                $message = 'Все группы китайского языка:'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= Config::AGE[$shedule->group_code].' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;

        case '/group':
            $message = 'Выберите возрастную группу:'.PHP_EOL.
                        '/1 - '.Config::AGE[1].PHP_EOL.
                        '/2 - '.Config::AGE[2].PHP_EOL.
                        '/3 - '.Config::AGE[3];
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;

        case '/1':
            $shedules = DB::table('shedules')->where('group_code', '=', 1)->get();
            if (count($shedules) == 0) {
                $message = Config::NO_GROUP;
            } else {
                $message = 'Все занятия младшей группы:'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= Config::LANG[$shedule->language_code].' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/2':
            $shedules = DB::table('shedules')->where('group_code', '=', 2)->get();
            if (count($shedules) == 0) {
                $message = Config::NO_GROUP;
            } else {
                $message = 'Все занятия средней группы:'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= Config::LANG[$shedule->language_code].' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/3':
            $shedules = DB::table('shedules')->where('group_code', '=', 3)->get();
            if (count($shedules) == 0) {
                $message = Config::NO_GROUP;
            } else {
                $message = 'Все занятия старшей группы:'.PHP_EOL;
                foreach ($shedules as $shedule) {
                    $message .= Config::LANG[$shedule->language_code].' '.$shedule->date.' '.$shedule->teacher.PHP_EOL;
                }
            }
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        case '/help':
            $message = 'Список доступных команд:
                        /start - начало работы с ботом
                        /teachers - преподаватели
                        /cost - информация о стоимости занятий
                        /schedule - информация о стоимости занятий
                        /help - выводит данный список';
            $telegram->sendMessage(['chat_id' => $chat, 'text' => $message]);
            break;
        default:
            $telegram->sendMessage(['chat_id' => $chat, 'text' => 'Команда не распознана']);
            break;
    }

    return response('OK', 200);
});
