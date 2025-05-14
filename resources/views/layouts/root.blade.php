@php
/**
 * @var string $appLocale
 */
@endphp
<!DOCTYPE html>
<html lang="{{ $appLocale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ToDo: список дел для организации работы и жизни')</title>
    <meta name="description" content="@yield('description', 'Таск-менеджер и приложение для ведения списка дел. Обретите сосредоточенность, организованность и спокойствие.')">

    @yield('head')
    @yield('head-bottom')
</head>
<body>
    @yield('body')
    @yield('body-bottom')
    @stack('scripts-bottom')
</body>
</html>