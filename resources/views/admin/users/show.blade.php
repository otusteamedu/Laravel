@extends('layouts.admin')

@section('title', $user->name)

@php
    /**
     * @var \App\Application\UseCases\User\DTO\UserDTO $user
     */
@endphp

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1>Просмотр пользователя</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i> К списку
                </a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Редактировать
                </a>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title h5 mb-0">{{ $user->name }}</h3>
                    {{-- <div>
                         @if($user->isAdmin)
                             <span class="badge bg-danger">Администратор</span>
                         @else
                             <span class="badge bg-secondary">Пользователь</span>
                         @endif
                     </div>--}}
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>ID:</strong> {{ $user->id }}</p>
                        <p class="mb-1"><strong>Имя:</strong> {{ $user->name }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                        {{-- <p class="mb-1"><strong>Статус:</strong> {{ $user->isAdmin ? 'Администратор' : 'Пользователь' }}</p>--}}
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Регистрация:</strong> {{ $user->createdAt->format('d.m.Y H:i') }}</p>
                        <p class="mb-1"><strong>Последнее
                                обновление:</strong> {{ $user->updatedAt->format('d.m.Y H:i') }}</p>
                        <p class="mb-1"><strong>Email
                                подтвержден:</strong> {{ $user->emailVerifiedAt ? $user->emailVerifiedAt->format('d.m.Y H:i') : 'Нет' }}
                        </p>
                    </div>
                </div>


                @if($user->id !== auth()->id())
                    {{-- todo убрать использование auth--}}
                    <div class="d-flex justify-content-end mt-4">
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                              onsubmit="return confirm('Вы уверены? Это действие может быть необратимо.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> Удалить пользователя
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
