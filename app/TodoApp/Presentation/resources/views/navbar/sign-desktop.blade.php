@guest
    <div class="">
        <a href="{{ route('login') }}" class="btn link-warning me-2">
            <i class="fa-solid fa-arrow-right-to-bracket pe-2"></i>{{ __('Log In') }}
        </a>
        <a href="{{ route('register') }}" class="btn btn-outline-secondary me-2">
            <i class="fa-solid fa-user-plus pe-2"></i>{{ __('Register') }}
        </a>
    </div>
@endguest
@auth
    <div class="align-items-center d-flex">
        <div class="px-2"><a href="{{ route('profile.edit') }}" class="nav-link">{{ Auth::user()->name }}</a></div>
        <a href="/logout" class="btn link-secondary">
            {{ __('Log Out') }}<i class="fa-solid fa-arrow-right-from-bracket ps-2"></i>
        </a>
    </div>
@endauth
