@extends('layouts.main')
@section('title', 'ToDo - Регистрация')
@section('deription', 'Зарегистрируйтесь и начните работать с сервисом ToDo')

@section('content')
    <div class="mx-auto" style="max-width: 400px;">
        <h4 class="mt-3 text-center">Регистрация</h4>

        <x-register-form />
        
        <p class="text-center py-3">Уже зарегистированы? <a href="/login" class="text-decoration-none ms-2">Войти</a></p>
    </div>
@endsection
