@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <h1>Добро пожаловать на наш сайт!</h1>
    <p>Мы рады видеть вас здесь. Это наш проект, где вы можете найти полезную информацию, зарегистрироваться и просматривать свой профиль.</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Наша цель</h5>
                    <p class="card-text">Создать простой и красивый интерфейс для пользователей, где каждый может чувствовать себя комфортно.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3 mt-md-0">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Возможности</h5>
                    <ul>
                        <li>Регистрация и авторизация</li>
                        <li>Просмотр пользовательского профиля</li>
                        <li>Чтение статей</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-3 mt-md-0">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Обратная связь</h5>
                    <p class="card-text">Если у вас есть предложения или замечания — напишите нам! Мы открыты к улучшениям.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
