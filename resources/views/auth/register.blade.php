@extends('todo-app::layouts.main')
@section('title', 'ToDo - Регистрация')
@section('deription', 'Зарегистрируйтесь и начните работать с сервисом ToDo')

@section('content')
    <div class="mx-auto" style="max-width: 400px;">
        <h4 class="mt-3 text-center">{{ __('Register') }}</h4>

        <div class="divider-text">
            <span class="bg-light">{{ mb_strtoupper(__('Get started with your social media account')) }}</span>
        </div>
        @include('todo-app::partials.social-login')

        <div class="divider-text">
            <span class="bg-light">{{ __('OR') }}</span>
        </div>

        <x-todo-app::register-form />
        
        <p class="text-center py-3">{{ __('Already registered?') }} <a href="{{ route('login') }}" class="text-decoration-none ms-2">{{ __('Log in') }}</a></p>
    </div>
@endsection
