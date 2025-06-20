@extends('layouts.public')

@section('title', 'Редактировать задачу')

@section('content')
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Редактировать задачу</h1>
                <p class="text-gray-600 mt-1">Редактирование: {{ $task->title }}</p>
            </div>
            <a href="{{ route('tasks.index') }}" 
               class="btn-secondary">
                Назад к списку
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="p-6">
        @if($errors->any())
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="form-label required">
                    Название задачи
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $task->title) }}"
                       required
                       placeholder="Введите название задачи"
                       class="custom-input">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="form-label">
                    Описание
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          placeholder="Введите описание задачи (необязательно)"
                          class="custom-textarea">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Executor -->
                <div>
                    <label for="executor_id" class="form-label required">
                        Исполнитель
                    </label>
                    <select id="executor_id" 
                            name="executor_id" 
                            required
                            class="custom-select w-full">
                        <option value="">Выберите исполнителя</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('executor_id', $task->executorId) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="form-label required">
                        Категория
                    </label>
                    <select id="category_id" 
                            name="category_id" 
                            required
                            class="custom-select w-full">
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $task->categoryId) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Priority -->
                <div>
                    <label for="priority_id" class="form-label required">
                        Приоритет
                    </label>
                    <select id="priority_id" 
                            name="priority_id" 
                            required
                            class="custom-select w-full">
                        <option value="">Выберите приоритет</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority->id }}" {{ old('priority_id', $task->priorityId) == $priority->id ? 'selected' : '' }}>
                                {{ $priority->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="form-label">
                        Статус
                    </label>
                    <select id="status" 
                            name="status"
                            class="custom-select w-full">
                        <option value="новая" {{ old('status', $task->status) == 'новая' ? 'selected' : '' }}>Новая</option>
                        <option value="в работе" {{ old('status', $task->status) == 'в работе' ? 'selected' : '' }}>В работе</option>
                        <option value="выполнена" {{ old('status', $task->status) == 'выполнена' ? 'selected' : '' }}>Выполнена</option>
                        <option value="отменена" {{ old('status', $task->status) == 'отменена' ? 'selected' : '' }}>Отменена</option>
                    </select>
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="form-label">
                        Срок выполнения
                    </label>
                    <input type="date" 
                           id="due_date" 
                           name="due_date" 
                           value="{{ old('due_date', $task->dueDate?->format('Y-m-d')) }}"
                           class="custom-input">
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('tasks.index') }}" 
                   class="btn-secondary">
                    Отмена
                </a>
                <button type="submit" 
                        class="btn-primary">
                    Обновить задачу
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 