@extends('layouts.public')

@section('title', 'Задачи')

@section('content')
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Задачи</h1>
                <p class="text-gray-600 mt-1">Управление задачами и отслеживание прогресса</p>
            </div>
            <a href="{{ route('tasks.create') }}" 
               class="btn-primary flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Новая задача
            </a>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="p-6 border-b border-gray-200">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
            <div class="bg-gray-50 rounded-lg p-4 text-center border border-blue-200">
                <div class="text-2xl font-bold text-gray-900">{{ $tasks->total() }}</div>
                <div class="text-sm text-gray-600">Всего задач</div>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">0</div>
                <div class="text-sm text-gray-600">Новые</div>
            </div>
            <div class="bg-orange-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-orange-600">0</div>
                <div class="text-sm text-gray-600">В работе</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-600">0</div>
                <div class="text-sm text-gray-600">Завершенные</div>
            </div>
            <div class="bg-red-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-red-600">0</div>
                <div class="text-sm text-gray-600">Просроченные</div>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">0</div>
                <div class="text-sm text-gray-600">В избранном</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">0</div>
                <div class="text-sm text-gray-600">Личные</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-gray-600">0</div>
                <div class="text-sm text-gray-600">Командные</div>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="flex">
        <!-- Sidebar with Filters -->
        <div class="w-64 border-r border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Фильтры</h3>
            
            <!-- Status Filter -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Статус</h4>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mr-2">
                        <span class="text-sm text-gray-600">Новый</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mr-2">
                        <span class="text-sm text-gray-600">В работе</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mr-2">
                        <span class="text-sm text-gray-600">Завершен</span>
                    </label>
                </div>
            </div>

            <!-- Priority Filter -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Приоритет</h4>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mr-2">
                        <span class="text-sm text-gray-600">Высокий</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mr-2">
                        <span class="text-sm text-gray-600">Средний</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mr-2">
                        <span class="text-sm text-gray-600">Низкий</span>
                    </label>
                </div>
            </div>

            <!-- Additional Filters -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" class="rounded border-gray-300 text-blue-600 mr-2">
                    <span class="text-sm text-gray-600">Только избранные</span>
                </label>
            </div>

            <!-- Sort Options -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Сортировать по</h4>
                <select class="custom-select w-full">
                    <option>Дате создания</option>
                    <option>Приоритету</option>
                    <option>Дедлайну</option>
                </select>
            </div>

            <!-- Sort Direction -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Направление сортировки</h4>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="sort_direction" class="text-blue-600 mr-2" checked>
                        <span class="text-sm text-gray-600">По возрастанию</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="sort_direction" class="text-blue-600 mr-2">
                        <span class="text-sm text-gray-600">По убыванию</span>
                    </label>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="space-y-2">
                <button class="btn-primary w-full">
                    Применить
                </button>
                <button class="btn-secondary w-full">
                    Сбросить
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Все задачи</h2>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Поиск задач..."
                               class="search-input w-64">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            @if($tasks->count() > 0)
                <!-- Tasks Grid -->
                <div class="space-y-4">
                    @foreach($tasks as $task)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                                        <a href="{{ route('tasks.show', $task->id) }}" class="hover:text-blue-600 transition-colors">
                                            {{ $task->title }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($task->description, 150) }}</p>
                                    
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $task->executorName ?: 'Не назначен' }}
                                        </span>
                                        
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $task->categoryName ?: 'Без категории' }}
                                        </span>

                                        @if($task->dueDate)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $task->dueDate->format('d.m.Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2 ml-4">
                                    <!-- Status Badge -->
                                    @if($task->status === 'новая')
                                        <span class="status-badge status-new">{{ $task->status }}</span>
                                    @elseif($task->status === 'в работе')
                                        <span class="status-badge status-in-progress">{{ $task->status }}</span>
                                    @elseif($task->status === 'выполнена')
                                        <span class="status-badge status-completed">{{ $task->status }}</span>
                                    @else
                                        <span class="status-badge status-cancelled">{{ $task->status }}</span>
                                    @endif

                                    <!-- View Button -->
                                    <a href="{{ route('tasks.show', $task->id) }}" 
                                       class="text-gray-600 hover:text-gray-800"
                                       title="Просмотр">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <!-- Edit Button -->
                                    @can('update', $taskModels[$task->id])
                                        <a href="{{ route('tasks.edit', $task->id) }}" 
                                           class="text-blue-600 hover:text-blue-800"
                                           title="Редактировать">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $tasks->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v11a2 2 0 002 2h9.5M15 8.5l3-3m0 0l3-3m-3 3h11a2 2 0 012 2v11a2 2 0 01-2 2H9"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Задачи не найдены</h3>
                    <p class="text-gray-600 mb-4">Попробуйте изменить фильтры или создайте новую задачу</p>
                    <a href="{{ route('tasks.create') }}" 
                       class="btn-primary">
                        Создать задачу
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 