@extends('layouts.app')

@section('title', 'Пользователи')

@section('content')
@if ($name && $email && $age && $bio)
    <div class="container d-flex justify-content-center align-items-start min-vh-100">
        <div class="card shadow mt-5 p-4 w-100" style="max-width: 500px;">
            <h1>Профиль пользователя</h1>
            <p><strong>Имя:</strong> {{ $name }}</p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Возраст:</strong> {{ $age }}</p>
            <p><strong>О себе:</strong> {{ $bio }}</p>
            <a href="{{ route('user') }}" class="btn btn-secondary mt-3">← Назад к списку</a>
        </div>
    </div>
@else
    <div class="container mt-5">
        <h1>Список пользователей</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <ul class="list-group">
                    @foreach($users as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $user['name'] }}</strong><br>
                                <small>{{ $user['email'] }}</small>
                            </div>
                            <a href="{{ route('user', $user) }}" class="btn btn-sm btn-primary">Подробнее</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
@endsection