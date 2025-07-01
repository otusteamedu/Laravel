<?php

if (! function_exists('current_locale')) {
    /**
     * Получить текущую локаль
     *
     * @return string
     */
    function current_locale(): string
    {
        return app()->getLocale();
    }
}

if (! function_exists('localized_route')) {
    /**
     * Генерировать локализованный URL
     *
     * @param string $route
     * @param array $parameters
     * @param string|null $locale
     * @return string
     */
    function localized_route(string $route, array $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?? current_locale();
        $parameters['locale'] = $locale;
        
        return route($route, $parameters);
    }
}

if (! function_exists('switch_locale_url')) {
    /**
     * Получить URL для переключения локали
     *
     * @param string $locale
     * @return string
     */
    function switch_locale_url(string $locale): string
    {
        $currentRoute = request()->route();
        if (!$currentRoute) {
            return url('/');
        }

        $routeName = $currentRoute->getName();
        $parameters = $currentRoute->parameters();
        
        // Если это локализованный роут, меняем локаль
        if (str_starts_with($routeName, 'localized.')) {
            $parameters['locale'] = $locale;
            return route($routeName, $parameters);
        }
        
        return url('/');
    }
}

if (! function_exists('supported_locales')) {
    /**
     * Получить список поддерживаемых локалей
     *
     * @return array
     */
    function supported_locales(): array
    {
        return config('localization.supported', []);
    }
} 