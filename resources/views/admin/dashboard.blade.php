@extends('layouts.admin')

@section('title', 'Дашборд')

@section('header', 'Дашборд')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Пользователи</h5>
                        <h2 class="mb-0">{{ $stats['users'] }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-white mt-3 d-block">Подробнее <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Категории</h5>
                        <h2 class="mb-0">{{ $stats['categories'] }}</h2>
                    </div>
                    <i class="fas fa-folder fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="text-white mt-3 d-block">Подробнее <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Задачи</h5>
                        <h2 class="mb-0">{{ $stats['tasks'] }}</h2>
                    </div>
                    <i class="fas fa-tasks fa-3x opacity-50"></i>
                </div>
                <a href="{{ route('admin.tasks.index') }}" class="text-white mt-3 d-block">Подробнее <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Последние задачи</h5>
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-sm btn-primary">Все задачи</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Категория</th>
                                <th>Пользователь</th>
                                <th>Создана</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['recent_tasks'] as $task)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.tasks.edit', $task->id) }}">{{ $task->title }}</a>
                                    </td>
                                    <td>{{ $task->category->name ?? 'Нет категории' }}</td>
                                    <td>{{ $task->user->name ?? 'Нет пользователя' }}</td>
                                    <td>{{ $task->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Задач пока нет</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Новые пользователи</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">Все пользователи</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Дата регистрации</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['recent_users'] as $user)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}">{{ $user->name }}</a>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Пользователей пока нет</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 