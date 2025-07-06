@extends('layouts.admin')

@section('title', 'Управление пользователями')
@php
    /**
     * @var Illuminate\Pagination\LengthAwarePaginator $users
     */

    /**
     * @var \App\Application\UseCases\User\DTO\UserDTO $user
     */
@endphp
@section('content')
    <div class="container-fluid px-0">
        <div class="row mb-4">
            <div class="col-md-6 mb-2 mb-md-0">
                <h1 class="h2">Управление пользователями</h1>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Добавить пользователя
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Статус</th>
                                <th class="d-none d-md-table-cell">Регистрация</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="text-decoration-none">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td class="text-break">{{ $user->email }}</td>
                                    <td>
                                        @if(in_array('admin', $user->roles))
                                            <span class="badge bg-danger">Админ</span>
                                        @else
                                            <span class="badge bg-secondary">Пользователь</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $user->createdAt->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                               class="btn btn-outline-primary" title="Просмотр">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                               class="btn btn-outline-secondary" title="Редактировать">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Вы уверены? Это действие может быть необратимо.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger"
                                                            title="Удалить">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Пользователей пока нет</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <nav>
                            {{ $users->links() }}
                        </nav>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Пользователи не найдены.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
