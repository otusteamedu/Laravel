<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Админ-панель</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: #f8f9fa;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar .active {
            background-color: #007bff;
        }
        .content {
            padding: 20px;
        }
        .admin-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .user-actions {
            display: flex;
            align-items: center;
        }
        .logout-form {
            margin-left: 15px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Боковое меню -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h5>Админ-панель</h5>
                </div>
                <nav>
                    <a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) active @endif">
                        <i class="fas fa-tachometer-alt me-2"></i> Дашборд
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="@if(request()->routeIs('admin.users.*')) active @endif">
                        <i class="fas fa-users me-2"></i> Пользователи
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="@if(request()->routeIs('admin.categories.*')) active @endif">
                        <i class="fas fa-folder me-2"></i> Категории
                    </a>
                    <a href="{{ route('admin.tasks.index') }}" class="@if(request()->routeIs('admin.tasks.*')) active @endif">
                        <i class="fas fa-tasks me-2"></i> Задачи
                    </a>
                    <a href="{{ route('admin.cache.index') }}" class="@if(request()->routeIs('admin.cache.*')) active @endif">
                        <i class="fas fa-memory me-2"></i> Кэш
                    </a>
                </nav>
            </div>

            <!-- Основной контент -->
            <div class="col-md-10 p-0">
                <header class="admin-header d-flex justify-content-between align-items-center">
                    <h4 class="m-0">@yield('header', 'Админ-панель')</h4>
                    <div class="user-actions">
                        <span class="user-name">Администратор</span>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form d-inline-block">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-sign-out-alt"></i> Выход
                            </button>
                        </form>
                    </div>
                </header>
                <main class="content">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Закрывать алерты автоматически через 5 секунд
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>

    @yield('scripts')
</body>
</html>
