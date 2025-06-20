@extends('layouts.admin')

@section('title', 'Создание задачи')

@section('header', 'Создание задачи')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Новая задача</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.tasks.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Название</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror"
                       id="title" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="executor_id" class="form-label">Исполнитель</label>
                    <select class="form-select @error('user_id') is-invalid @enderror"
                            id="executor_id" name="executor_id" required>
                        <option value="">Выберите исполнителя</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('executor_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Категория</label>
                    <select class="form-select @error('category_id') is-invalid @enderror"
                            id="category_id" name="category_id" required>
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="priority_id" class="form-label">Приоритет</label>
                    <select class="form-select @error('priority_id') is-invalid @enderror"
                            id="priority_id" name="priority_id" required>
                        <option value="">Выберите приоритет</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority->id }}" {{ old('priority_id') == $priority->id ? 'selected' : '' }}>
                                {{ $priority->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('priority_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">Статус</label>
                    <select class="form-select @error('status') is-invalid @enderror"
                            id="status" name="status" required>
                        <option value="новая" {{ old('status') == 'новая' ? 'selected' : '' }}>Новая</option>
                        <option value="в работе" {{ old('status') == 'в работе' ? 'selected' : '' }}>В работе</option>
                        <option value="выполнена" {{ old('status') == 'выполнена' ? 'selected' : '' }}>Выполнена</option>
                        <option value="отменена" {{ old('status') == 'отменена' ? 'selected' : '' }}>Отменена</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="due_date" class="form-label">Срок выполнения</label>
                    <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                           id="due_date" name="due_date" value="{{ old('due_date') }}">
                    @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Назад
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Сохранить
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
