@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body text-center">
                    <img src="https://jjji.ru/150x150" class="rounded-circle mb-3" alt="Аватар пользователя" width="120" height="120">
                    <h3 class="card-title mb-1">{{ auth()->user()->name ?? 'user_name'}}</h3>
                    <p class="text-muted mb-3">Веб-разработчик</p>
                    <p>Люблю создавать современные сайты и приложения. Открыт для новых проектов и сотрудничества.</p>
                    <ul class="list-inline mb-3">
                        <li class="list-inline-item">
                            <a href="mailto:ivan@example.com" class="text-decoration-none">
                                <i class="bi bi-envelope"></i> {{ auth()->user()->email ?? 'user_email' }}
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="tel:+79991234567" class="text-decoration-none">
                                <i class="bi bi-phone"></i> +7 (999) 123-45-67
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="text-decoration-none">
                                <i class="bi bi-telegram"></i> Telegram
                            </a>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-primary">{{ __('pages.home.edit_profile') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
