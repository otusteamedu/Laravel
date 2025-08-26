<?php

function detectLocaleFromBrowser(array $supportedLocales = ['en', 'ru'], string $default = 'en'): string
{
    $browserLanguages = request()->getLanguages(); // ['ru-RU', 'ru', 'en-US', 'en']

    foreach ($browserLanguages as $lang) {
        $lang = substr($lang, 0, 2); // 'ru-RU' => 'ru'
        if (in_array($lang, $supportedLocales)) {
            return $lang;
        }
    }

    return $default;
}
