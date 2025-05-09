@guest
    <div class="">
        <a href="/login" class="btn link-warning me-2">
            <i class="fa-solid fa-arrow-right-to-bracket pe-2"></i>Вход
        </a>
        <a href="/register" class="btn btn-outline-secondary me-2">
            <i class="fa-solid fa-user-plus pe-2"></i>Регистрация
        </a>
    </div>
@endguest
@auth
    <div class="align-items-center d-flex">
        <div class="px-2"><a href="/profile" class="nav-link">{{ Auth::user()->name }}</a></div>
        <a href="/logout" class="btn link-secondary">
            Выйти<i class="fa-solid fa-arrow-right-from-bracket ps-2"></i>
        </a>
    </div>
@endauth
