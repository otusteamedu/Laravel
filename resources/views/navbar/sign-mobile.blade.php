@guest
    <li class="nav-item">
        <a href="{{ route('login') }}" class="nav-link link-warning">
            <i class="fa-solid fa-arrow-right-to-bracket pe-2"></i></i>Вход
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('register') }}">
            <i class="fa-solid fa-user-plus pe-2"></i>Регистрация
        </a>
    </li>  
@endguest
@auth
    <li class="nav-item">
        <a href="{{ route('logout') }}" class="nav-link" aria-current="page" >
            <i class="fa-solid fa-arrow-right-from-bracket pe-2"></i>Выйти
        </a>
    </li>   
@endauth