<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3 sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                    Панель управления
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}" href="{{ route('admin.news.index') }}">
                    <span class="nav-icon"><i class="fas fa-newspaper"></i></span>
                    Новости
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    <span class="nav-icon"><i class="fas fa-list"></i></span>
                    Категории
                </a>
            </li>
           {{-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}" href="{{ route('admin.comments.index') }}">
                    <span class="nav-icon"><i class="fas fa-comment"></i></span>
                    Комментарии
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    Пользователи
                </a>
            </li>--}}

        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Аккаунт</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                        Выход
                    </a>
                </form>
            </li>
        </ul>
    </div>
</nav>
