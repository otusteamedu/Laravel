@extends('layouts.admin')

@section('title', 'Панель управления')
@section('heading', 'Панель управления')

@section('content')
    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="card border-primary h-100">
                <div class="card-body text-center">
                    <i class="fas fa-newspaper fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Новости</h5>
                    <h2 class="display-6 fw-bold">0</h2>
                    <p class="card-text">Всего новостей в системе</p>
                    <a href="#" class="btn btn-primary">Управление новостями</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-success h-100">
                <div class="card-body text-center">
                    <i class="fas fa-list fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Категории</h5>
                    <h2 class="display-6 fw-bold">0</h2>
                    <p class="card-text">Всего категорий в системе</p>
                    <a href="#" class="btn btn-success">Управление категориями</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-warning h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Пользователи</h5>
                    <h2 class="display-6 fw-bold">0</h2>
                    <p class="card-text">Всего пользователей в системе</p>
                    <a href="#" class="btn btn-warning">Управление пользователями</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-info h-100">
                <div class="card-body text-center">
                    <i class="fas fa-comments fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Комментарии</h5>
                    <h2 class="display-6 fw-bold">0</h2>
                    <p class="card-text">Всего комментариев в системе</p>
                    <a href="#" class="btn btn-info">Управление комментариями</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
    </div>
@endsection
