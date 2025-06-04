@extends ('layouts.root')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/style.css') }}"  rel="stylesheet">
@endsection

@section('head-bottom')
    <div class="container">
        <div class="row">
            <div class="col-3 menu-item">
                <a href="{{ route('page') }}">Главная </a>
            </div>
            <div class="col-3 menu-item">
                <a href="{{ route('reg') }}">Страница регистрации</a>
            </div>
            <div class="col-3 menu-item">
                <a href="{{ route('user') }}">Страница пользователя</a>
            </div>
            <div class="col-3 menu-item">
                <a href="{{ route('about') }}">Абстрактная страница (about) </a>
            </div>

        </div>
    </div>
@endsection

@section ('body')
    <div class="row text-center">
        @yield('content')

    </div>
@endsection


