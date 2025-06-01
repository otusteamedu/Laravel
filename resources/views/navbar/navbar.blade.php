<nav class="navbar navbar-dark bg-dark flex-nowrap">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ Vite::asset('resources/images/logo.png') }}" class="d-inline-block" alt="ToDo Logo" />
            <span class="align-middle ms-2">ToDo</span>
        </a>
        <div class="navbar-collapse desktop-nav-menu d-md-flex w-100">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 flex-row">
                <li class="nav-item">
                  <a href="{{ route('about') }}" 
                    @class([
                        'nav-link', 
                        'active' => request()->path() === trim(route(name: 'about', absolute: false ), '/')
                    ])>О сервисе</a>
                </li>
                @endphp
            </ul>
        </div>

        <button class="ms-auto navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobile-nav-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="collapse position-absolute w-100 bg-dark navbar-dark d-md-none nav-left" id="mobile-nav-menu">
    <div class="align-items-start bg-body-light d-flex flex-column px-3 shadow-3">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                    @class([
                        'nav-link', 
                        'active' => request()->path() === trim(route(name: 'about', absolute: false ), '/')
                    ])><i class="fa-solid fa-circle-check pe-2"></i>О сервисе
                </a>
            </li>
        </ul>
    </div>
</div>
