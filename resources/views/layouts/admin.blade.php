<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - @yield('title', 'Панель управления')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #212529;
        }
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            font-weight: 500;
            color: rgba(255, 255, 255, .75);
            padding: .75rem 1rem;
        }
        .sidebar .nav-link:hover {
            color: #fff;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, .1);
        }
        .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
            font-size: 1rem;
            background-color: rgba(0, 0, 0, .25);
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
        }
        .main-content {
            padding: 20px;
        }
        .nav-icon {
            width: 24px;
            margin-right: 10px;
            text-align: center;
        }

        /* Адаптивные стили */
        @media (max-width: 767.98px) {
            .sidebar {
                position: static;
                height: auto;
                padding-top: 0;
            }
            .sidebar-sticky {
                height: auto;
            }
            main {
                margin-left: 0 !important;
            }
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .d-md-none-custom {
                display: none;
            }
            .card-header form .row > div {
                margin-bottom: 0.5rem;
            }
            .btn-group-sm .btn {
                padding: .25rem .4rem;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 text-center" href="{{ route('admin.dashboard') }}">Админ-панель</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="w-100 d-none d-md-block"></div>
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <a class="nav-link px-3" href="{{ route('home', ['locale' => app()->getLocale()]) }}">
                <i class="fas fa-home"></i> <span class="d-none d-sm-inline">На сайт</span>
            </a>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        @include('admin.menu.index')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">@yield('heading', 'Панель управления')</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    @yield('actions')
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')
</body>
</html>
