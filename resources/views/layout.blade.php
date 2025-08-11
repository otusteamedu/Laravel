<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased dark:bg-slate-800 dark:text-white/50 min-h-screen flex flex-col">
    @include('header')

    <div class="flex-grow px-6 py-8">
        <div class="flex justify-between container mx-auto">
            <div class="w-full lg:w-8/12">
                @yield('content')
            </div>
        </div>
    </div>

    @include('footer')
</body>
</html>
