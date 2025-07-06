<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@22.6.0/dist/css/suggestions.min.css" rel="stylesheet" />
    <script src="https://api-maps.yandex.ru/2.1/?apikey=a46e85a0-6711-4c3f-a275-beb7118ecbb3&lang=ru_RU" type="text/javascript">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@22.6.0/dist/js/jquery.suggestions.min.js"></script>

    <!-- Scripts -->
    @vite([
        'resources/sass/app.scss', 
        'resources/css/carousel.css', 
        'resources/css/about.css', 
        'resources/js/app.js', 
        'resources/js/site.js' 
    ])
</head>

<body class="d-flex flex-column h-100">
    <header data-bs-theme="dark">
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('welcome') }}">Смартлайн</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('catalog') }}">Каталог</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('about') }}">О магазине</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('chat.index') }}">Чат</a>
                        </li>

                        @can('employee-access')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.index') }}">Панель управления</a>
                            </li>
                        @endcan

                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Кабинет пользователя
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="nav-item">
                                        <a class="dropdown-item" href="{{ route('profile.edit', auth()->id()) }}">Профиль</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="dropdown-item" href="{{ route('profile.history', auth()->id()) }}">История заказов</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            Выйти
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>    
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Войти</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="nav-link">Регистрация</a>
                            </li>
                        @endauth
                    </ul>

                    <div>
                        <a href="{{ route('cart.index') }}">
                            <img src="{{ asset('images/cart.png') }}" alt="" width="30">
                        </a>
                        @include('cartcount::counter')
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-shrink-0">
        @yield('content')
    </main>

    <footer class="container footer py-5 mt-auto">
        <p class="float-end"><a href="#">Вверх</a></p>
        <p>&copy; 2025</p>
    </footer>
</body>

</html>