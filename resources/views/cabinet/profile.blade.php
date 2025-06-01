@extends('layouts.app')

@section('content')
<div class="bg-body-tertiary rounded-3">
    <div class="container py-3">
        <h1 class="display-5 fw-bold text-center">Профиль пользователя</h1>
    </div>
</div>

<div class="container mb-5">
    <div>
        @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div>
        @if (session('success'))
        <div class="alert alert-success mt-3">
            <span>{{ session('success') }}</span>
        </div>
        @endif
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Информация о пользователе</h5>
            <p class="card-text">Обновите данные своего аккаунта</p>
            
            <form class="col-md-8 col-xl-6" action="{{ route('profile.update', $userId) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $name }}">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') ?? $email }}">
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Смена пароля</h5>
            <p class="card-text">Создайте достаточно длинный и надежный пароль</p>
            
            <form class="col-md-8 col-xl-6" action="{{ route('profile.password', $userId) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="old_password" class="form-label">Старый пароль</label>
                    <input type="password" class="form-control" id="old_password">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Новый пароль</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Повторите новый пароль</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                <button type="submit" class="btn btn-primary">Сменить пароль</button>
            </form>
        </div>
    </div>
</div>
@endsection