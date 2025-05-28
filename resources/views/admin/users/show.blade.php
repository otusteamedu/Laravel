@extends('layouts.admin')

@section('content')
    <h1>Просмотр пользователя</h1>

    <div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-link">Вернуться</a>
    </div>

    <div class="row mt-3">
        <div class="card col-xl-8">
            <div class="card-body">
                <h4 class="card-title fw-bold">{{ $name }}</h4>
                <p class="card-text mt-3">Email: {{ $email }}</p>
                <p class="card-text">Email верифицирован: {{ $emailVerified }}</p>
                <p class="card-text">Роль: {{ $role }}</p>
                <h6 class="card-subtitle mb-2 text-body-secondary">Создан: {{ $createdAt }}</h6>
                <h6 class="card-subtitle mb-2 text-body-secondary">Обновлен: {{ $updatedAt }}</h6>
            </div>
        </div>
    </div>
@endsection