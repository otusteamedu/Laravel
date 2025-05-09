@extends('layouts.main')
@section('title', 'ToDo - Вход')
@section('deription', 'Авторизуйтесь и начните работать с сервисом ToDo')

@section('content')
    <div class="mx-auto" style="max-width: 400px;">
        <h4 class="mt-3 text-center">С возвращением!</h4>

        <x-login-form />

        <div class="text-center py-3">
            Еще не зарегистрированы? <a href="/register" class="text-decoration-none ms-2">Зарегистрироваться</a>
        </div>
    </div>
@endsection
