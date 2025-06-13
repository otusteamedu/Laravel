<nav class="navbar navbar-expand-md navbar-dark bg-dark flex-nowrap">
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
                    ])>{{ __('About the service') }}</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a href="{{ route('projects.index') }}" 
                            @class([
                                'nav-link', 
                                'active' => str_starts_with(request()->route()->getPrefix(), '/project')
                            ])>Проекты</a>
                    </li>
                @endauth
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <span class="nav-link dropdown-toggle" id="localeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-globe me-2"></i>
                        <span class="app-locale">{{ $locales[App::getLocale()] }}</span>
                    </span>
                    <ul class="dropdown-menu" aria-labelledby="localeDropdown">
                        @foreach($locales as $locale => $localeName)
                            <li><a class="dropdown-item" href="{{ route('locale.set',['locale' => $locale]) }}">{{ $localeName }}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
            <div class="navbar-nav">
                @include('navbar.sign-desktop')
            </div>
        </div>

        <button class="ms-auto navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobile-nav-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="collapse position-absolute w-100 bg-dark navbar-dark d-md-none nav-left" id="mobile-nav-menu">
    <div class="align-items-start bg-body-light d-flex flex-column px-3 shadow-3">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            @auth
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{ route('profile.edit') }}">
                        <i class="fa-solid fa-user pe-2"></i>{{ Auth::user()->name }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('projects.index') }}" 
                        @class([
                            'nav-link', 
                            'active' => request()->path() === trim(route(name: 'projects.index', absolute: false), '/')
                        ])><i class="fa-solid fa-diagram-project pe-2"></i>Проекты
                    </a>
                </li>
            @endauth
            <li class="nav-item">
                <a href="{{ route('about') }}" 
                    @class([
                        'nav-link', 
                        'active' => request()->path() === trim(route(name: 'about', absolute: false ), '/')
                    ])><i class="fa-solid fa-circle-check pe-2"></i>{{ __('About the service') }}
                </a>
            </li>
            <li class="accordion nav-item" id="accordionLocale">
                <a class="nav-link collapsed" role="button" data-bs-toggle="collapse" data-bs-target="#collapseLocale" aria-expanded="false" aria-controls="collapseLocale">
                        <i class="fa-solid fa-globe me-2"></i>
                        {{ $locales[App::getLocale()] }}
                </a>
                <div id="collapseLocale" class="accordion-collapse collapse ps-4" aria-labelledby="collapseLocale" data-bs-parent="#accordionLocale">
                    @foreach($locales as $locale => $localeName)
                        <a class="nav-link" href="{{ route('locale.set',['locale' => $locale]) }}">{{ $localeName }}</a>
                    @endforeach
                </div>
            </li>

            @include('navbar.sign-mobile')
        </ul>
    </div>
</div>
