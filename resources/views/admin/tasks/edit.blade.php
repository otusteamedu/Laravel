@extends('layouts.admin')

@section('title', 'Редактирование задачи')

@section('header', 'Редактирование задачи')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Редактирование задачи: {{ $task->title }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Название</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror"
                       id="title" name="title" value="{{ old('title', $task->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description" name="description" rows="3">{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="executor_id" class="form-label">Пользователь</label>
                    <select class="form-select @error('executor_id') is-invalid @enderror"
                            id="executor_id" name="executor_id" required>
                        <option value="">Выберите пользователя</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (old('executor_id', $task->executor_id) == $user->id) ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('executor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Категория</label>
                    <select class="form-select @error('category_id') is-invalid @enderror"
                            id="category_id" name="category_id" required>
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $task->category_id) == $category->id) ? 'selected' : '' }}>
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
                            <option value="{{ $priority->id }}" {{ (old('priority_id', $task->priority_id) == $priority->id) ? 'selected' : '' }}>
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
                        <option value="новая" {{ (old('status', $task->status) == 'новая') ? 'selected' : '' }}>Новая</option>
                        <option value="в работе" {{ (old('status', $task->status) == 'в работе') ? 'selected' : '' }}>В работе</option>
                        <option value="выполнена" {{ (old('status', $task->status) == 'выполнена') ? 'selected' : '' }}>Выполнена</option>
                        <option value="отменена" {{ (old('status', $task->status) == 'отменена') ? 'selected' : '' }}>Отменена</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="due_date" class="form-label">Срок выполнения</label>
                    <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                           id="due_date" name="due_date" value="{{ old('due_date', $task->due_date ? date('Y-m-d', strtotime($task->due_date)) : '') }}">
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
                    <i class="fas fa-save me-1"></i> Сохранить изменения
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
