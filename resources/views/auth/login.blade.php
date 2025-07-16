@extends('todo-app::layouts.main')
@section('title', 'ToDo - Вход')
@section('deription', 'Авторизуйтесь и начните работать с сервисом ToDo')

@section('content')
    <div class="mx-auto" style="max-width: 400px;">
        <h4 class="mt-3 text-center">{{ __('Welcome back!') }}</h4>

        <x-todo-app::login-form />

        <div class="divider-text">
            <span class=bg-light>{{ mb_strtoupper(__('or continue with')) }}</span>
        </div>
        @include('todo-app::partials.social-login')

        <div class="text-center py-3">
            {{ __('Not registered yet?') }} <a href="{{ route('register') }}" class="text-decoration-none ms-2">{{ __('Register') }}</a>
        </div>
    </div>
@endsection
