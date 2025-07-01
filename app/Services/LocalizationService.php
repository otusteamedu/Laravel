<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * Сервис для работы с локализацией
 */
class LocalizationService
{
    /**
     * Получить список поддерживаемых локалей
     *
     * @return array
     */
    public static function getSupportedLocales(): array
    {
        return Config::get('localization.supported', []);
    }

    /**
     * Получить локаль по умолчанию
     *
     * @return string
     */
    public static function getDefaultLocale(): string
    {
        return Config::get('localization.default', 'en');
    }

    /**
     * Проверить, поддерживается ли локаль
     *
     * @param string $locale
     * @return bool
     */
    public static function isSupported(string $locale): bool
    {
        return array_key_exists($locale, static::getSupportedLocales());
    }

    /**
     * Получить текущую локаль
     *
     * @return string
     */
    public static function getCurrentLocale(): string
    {
        return App::getLocale();
    }

    /**
     * Получить информацию о локали
     *
     * @param string $locale
     * @return array|null
     */
    public static function getLocaleInfo(string $locale): ?array
    {
        $supported = static::getSupportedLocales();
        return $supported[$locale] ?? null;
    }

    /**
     * Генерировать URL с локалью
     *
     * @param string $locale
     * @param string $route
     * @param array $parameters
     * @return string
     */
    public static function route(string $locale, string $route, array $parameters = []): string
    {
        $parameters['locale'] = $locale;
        return route($route, $parameters);
    }
} 