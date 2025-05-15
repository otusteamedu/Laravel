<ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <li class="nav-item {{ Route::currentRouteName() == 'contact' ? 'active' : '' }}">
        <a class="nav-link {{ Route::currentRouteName() == 'contact' ? 'active' : '' }}"  href="{{ route('contacts') }}">{{ __("main.contacts") }}</a>
    </li>
    <li class="nav-item}">
        <a class="nav-link {{ Route::currentRouteName() == 'registration' ? 'active' : '' }}" href="{{ route('registration') }}">{{ __("main.registration" )}}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Route::currentRouteName() == 'login' ? 'active' : '' }}"  href="{{ route('login') }}">{{ __("main.login") }}</a>
    </li>
</ul>
