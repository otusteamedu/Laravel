@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <h1>Создать аккаунт</h1>

    <form>
        <div class="mb-3">
            <label for="name" class="form-label">Логин</label>
            <input type="text" class="form-control" id="name" placeholder="Введите логин">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Электронная почта</label>
            <input type="email" class="form-control" id="email" placeholder="example@mail.ru">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" id="password" placeholder="Придумайте пароль">
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Подтверждение пароля</label>
            <input type="password" class="form-control" id="confirm_password" placeholder="Повторите пароль">
        </div>
        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
    </form>
@endsection
