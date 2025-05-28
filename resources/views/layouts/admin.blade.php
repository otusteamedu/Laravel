<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Панель управления</title>
    @vite(['resources/sass/app.scss', 'resources/css/sidebars.css', 'resources/js/app.js', 'resources/js/admin.js'])
</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="home" viewBox="0 0 16 16">
            <path
                d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z">
            </path>
        </symbol>
        <symbol id="table" viewBox="0 0 16 16">
            <path
                d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z">
            </path>
        </symbol>
        <symbol id="people-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
            <path fill-rule="evenodd"
                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z">
            </path>
        </symbol>
        <symbol id="grid" viewBox="0 0 16 16">
            <path
                d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z">
            </path>
        </symbol>
        <symbol id="catalog" viewBox='0 0 16 16'>
            <path class='accent' d='M3 11h18v8H3z'></path>
            <path class='outline'
                d='M19.5 6h-7.29a.47.47 0 0 1-.35-.15l-1.12-1.12A2.49 2.49 0 0 0 8.97 4H4.51a2.5 2.5 0 0 0-2.5 2.5v11a2.5 2.5 0 0 0 2.5 2.5h15a2.5 2.5 0 0 0 2.5-2.5v-9a2.5 2.5 0 0 0-2.5-2.5Zm-15 0h4.29c.13 0 .26.05.35.15l1.12 1.12c.47.47 1.1.73 1.77.73h7.46c.28 0 .5.22.5.5v1.85a3.45 3.45 0 0 0-1.5-.35H5.5c-.54 0-1.04.13-1.5.35V6.5c0-.28.22-.5.5-.5ZM20 17.5a.5.5 0 0 1-.5.5h-15a.5.5 0 0 1-.5-.5v-4c0-.83.67-1.5 1.5-1.5h13c.83 0 1.5.67 1.5 1.5v4Z'>
            </path>
            <path class='solid'
                d='M4.41 10h15.17c.87 0 1.7.25 2.41.72V8.5a2.5 2.5 0 0 0-2.5-2.5H12.2a.47.47 0 0 1-.35-.15l-1.12-1.12A2.49 2.49 0 0 0 8.96 4H4.5A2.5 2.5 0 0 0 2 6.5v4.22A4.34 4.34 0 0 1 4.41 10ZM19.59 12H4.41a2.43 2.43 0 0 0-2.42 2.42v3.09a2.5 2.5 0 0 0 2.5 2.5h15a2.5 2.5 0 0 0 2.5-2.5v-3.09A2.43 2.43 0 0 0 19.57 12Z'>
            </path>
        </symbol>
    </svg>

    <main class="d-flex flex-nowrap">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px;">
            <a href="{{ route('admin.index') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4">Панель управления</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" 
                        class="nav-link text-white @if(request()->routeIs('admin.index')) active @endif">
                        <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#home"></use>
                        </svg>
                        Главная
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" 
                        class="nav-link text-white @if(request()->routeIs('admin.categories.index')) active @endif">
                        <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#catalog"></use>
                        </svg>
                        Категории
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.products.index') }}" 
                        class="nav-link text-white @if(request()->routeIs('admin.products.index')) active @endif">
                        <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#grid"></use>
                        </svg>
                        Телефоны
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" 
                        class="nav-link text-white @if(request()->routeIs('admin.orders.index')) active @endif">
                        <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#table"></use>
                        </svg>
                        Заказы
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" 
                        class="nav-link text-white @if(request()->routeIs('admin.users.index')) active @endif">
                        <svg class="bi pe-none me-2" width="16" height="16" aria-hidden="true">
                            <use xlink:href="#people-circle"></use>
                        </svg>
                        Пользователи
                    </a>
                </li>
            </ul>
        </div>

        <div class="b-example-divider b-example-vr"></div>

        <div class="container-fluid ms-5 mt-2 mb-5 fs-5 overflow-auto">
            @yield('content')
        </div>
    </main>
</body>

</html>