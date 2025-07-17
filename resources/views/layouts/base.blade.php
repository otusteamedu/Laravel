<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'ТСЖ Радуга' }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <!-- Подключаем CSS -->
    <link href="{{ asset('css/bootstrap_5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/apartmentEdit.css') }}" rel="stylesheet">
    <link href="{{ asset('css/apartment_base.css') }}" rel="stylesheet">
    <link href="{{ asset('css/popup.css') }}" rel="stylesheet">
    <link href="{{ asset('css/apartment.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    @stack('css')
</head>

<body>
    <!-- Шапка -->
    <div style="position: relative;">
        <img src="{{ asset('img/header.jpg') }}" style="width:100%; height:auto;" alt="header">
        <div class="position-absolute" style="height:30px; background: rgba(89, 0, 168, 0.4); bottom: 10px; left: 0; right: 0;">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 text-start">
                        @unless ($title == 'ТСЖ Радуга')
                            <a href="{{ route('index') }}" class="my-link">Квартиры</a>
                            <span class="ms-3"></span>
                        @endunless
                        @unless ($title == 'Тарифы')
                            <a href="{{ route('tariffs.index') }}" class="my-link">Тарифы</a>
                            <span class="ms-3"></span>
                        @endunless
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="#" class="my-link" data-bs-toggle="modal" data-bs-target="#SettingsModal">Настройки</a>
                        <span class="ms-3"></span>
                        @auth
                            <!-- В реальном проекте здесь будет POST-запрос -->
                            <a href="{{ route('logout') }}" class="my-link">Выход</a>
                        @else
                            <a href="{{ route('login') }}" class="my-link">Вход</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        <div class="position-absolute top-50 start-50 translate-middle">
            <h1 class="text-center special">ТСЖ “Радуга”</h1>
        </div>
    </div>

    <!-- Блоки контента -->
    @yield('apartment_header')
    @yield('repair_header')
    @yield('content')

    <!-- Подключаем модальные окна (заглушки) -->
    @isset($showModals)
        <div class="modal fade" id="SettingsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Настройки</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Форма настроек будет здесь
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="LoginModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Вход</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Форма входа будет здесь
                    </div>
                </div>
            </div>
        </div>
    @endisset

    <!-- Подключаем JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/htmx.min.js') }}"></script>
    <script src="{{ asset('js/dialog.js') }}"></script>
    <script src="{{ asset('js/confirmation.js') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
</body>
</html>