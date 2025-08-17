<?php

return [
    'token' => env('TG_LOGGER_TOKEN'),
    'chat_id' => env('TG_LOGGER_CHAT_ID'),
    'topics' => [
        [
            'name' => 'Debug messages',
            'icon_color' => '9367192',
            'level' => 'debug',
            'id_topic'=>8
        ]
    ]
];
