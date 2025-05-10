@extends('layouts.app')

@section('content')
<div class="bg-body-tertiary rounded-3">
    <div class="container py-5">
        <h1 class="display-5 fw-bold text-center">Профиль пользователя</h1>
    </div>
</div>

<div class="container mb-5">
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Информация о пользователе</h5>
            <p class="card-text">Обновите данные своего аккаунта</p>
            
            <form class="col-md-8 col-xl-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" class="form-control" id="name">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email">
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Смена пароля</h5>
            <p class="card-text">Создайте достаточно длинный и надежный пароль</p>
            
            <form class="col-md-8 col-xl-6">
                <div class="mb-3">
                    <label for="old_password" class="form-label">Старый пароль</label>
                    <input type="password" class="form-control" id="old_password">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Новый пароль</label>
                    <input type="password" class="form-control" id="password">
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Повторите новый пароль</label>
                    <input type="password" class="form-control" id="password_confirmation">
                </div>
                <button type="submit" class="btn btn-primary">Сменить пароль</button>
            </form>
        </div>
    </div>
</div>
@endsection