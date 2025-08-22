<?php

return [
    'driver' => env('AUTO_TRANSLATOR_DRIVER', 'libretranslate'),

    'google' => [
        'api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
        'url' => env('GOOGLE_API_URL', 'https://translation.googleapis.com/language/translate/v2'),
    ],

    'yandex' => [
        'api_key' => env('YANDEX_TRANSLATE_API_KEY'),
        'folder_id' => env('YANDEX_FOLDER_ID'),
        'url' => env('YANDEX_API_URL', 'https://translate.api.cloud.yandex.net/translate/v2/translate'),
    ],

    'libretranslate' => [
        'url' => env('LIBRETRANSLATE_URL', 'http://host.docker.internal:5000'),
        'api_key' => env('LIBRETRANSLATE_API_KEY', ''),
    ],
];
