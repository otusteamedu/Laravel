<ul class="main-menu navbar-nav me-auto mb-2 mb-lg-0">
    <li class="nav-item whitespace-nowrap {{ Route::currentRouteName() == 'contact' ? 'active' : '' }}">
        <i class="fa-solid fa-map-location-dot block"></i><a class="nav-link {{ Route::currentRouteName() == 'contact' ? 'active' : '' }}"  href="{{ route('contacts') }}">{{ __("main.contacts") }}</a>
    </li>
    <li class="nav-item">
        <i class="fa-solid fa-address-card block"></i><a class="nav-link {{ Route::currentRouteName() == 'register' ? 'active' : '' }}" href="{{ route('register') }}">{{ __("main.registration" )}}</a>
    </li>
    <li class="nav-item">
        <i class="fa-solid fa-right-to-bracket block"></i><a class="nav-link {{ Route::currentRouteName() == 'login' ? 'active' : '' }}"  href="{{ route('login') }}">{{ __("main.login") }}</a>
    </li>
</ul>
