@extends('layouts.public')

@section('title', $task->title . ' - TodoApp')

@section('content')
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                    <p class="text-gray-600 mt-1">Детальная информация о задаче</p>
                </div>
            </div>
            @can('update', $taskModel)
                <a href="{{ route('tasks.edit', $task->id) }}" class="btn-primary flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Редактировать
                </a>
            @endcan
        </div>
    </div>

    <!-- Task Status and Meta -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-4">
            <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $task->status)) }}">
                {{ $task->status }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ $task->priorityName }}
            </span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                {{ $task->categoryName }}
            </span>
            @if($task->dueDate)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->dueDate->isPast() && $task->status !== 'завершена' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $task->dueDate->format('d.m.Y') }}
                    @if($task->dueDate->isPast() && $task->status !== 'завершена')
                        (просрочена)
                    @endif
                </span>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Описание</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if($task->description)
                            <p class="text-gray-700 leading-relaxed">{{ $task->description }}</p>
                        @else
                            <p class="text-gray-500 italic">Описание не указано</p>
                        @endif
                    </div>
                </div>

                <!-- Additional Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Дополнительная информация</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Дата создания</h4>
                                <p class="text-gray-600">{{ $task->createdAt->format('d.m.Y в H:i') }}</p>
                            </div>
                            @if($task->updatedAt != $task->createdAt)
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Последнее обновление</h4>
                                <p class="text-gray-600">{{ $task->updatedAt->format('d.m.Y в H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Creator -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Создатель</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-medium text-sm">
                                    {{ strtoupper(substr($task->creatorName, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $task->creatorName ?: 'Неизвестен' }}</p>
                                <p class="text-sm text-gray-600">ID: {{ $task->creatorId }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Executor -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Исполнитель</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-medium text-sm">
                                    {{ strtoupper(substr($task->executorName, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $task->executorName ?: 'Не назначен' }}</p>
                                <p class="text-sm text-gray-600">ID: {{ $task->executorId }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Details -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Категория</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $task->categoryColor }}"></div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $task->categoryName ?: 'Без категории' }}</p>
                                <p class="text-sm text-gray-600">ID: {{ $task->categoryId }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Priority -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Приоритет</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center space-x-2">
                            @php
                                $priorityColors = [
                                    'низкий' => 'text-green-600',
                                    'средний' => 'text-yellow-600', 
                                    'высокий' => 'text-red-600',
                                    'критический' => 'text-red-800'
                                ];
                                $priorityColor = $priorityColors[strtolower($task->priorityName)] ?? 'text-gray-600';
                            @endphp
                            <span class="w-3 h-3 rounded-full {{ str_replace('text-', 'bg-', $priorityColor) }}"></span>
                            <span class="font-medium {{ $priorityColor }}">{{ $task->priorityName }}</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Временная шкала</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Создана:</span>
                                <span class="text-gray-900">{{ $task->createdAt->format('d.m.Y') }}</span>
                            </div>
                            @if($task->updatedAt != $task->createdAt)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Обновлена:</span>
                                <span class="text-gray-900">{{ $task->updatedAt->format('d.m.Y') }}</span>
                            </div>
                            @endif
                            @if($task->dueDate)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Дедлайн:</span>
                                <span class="text-gray-900 {{ $task->dueDate->isPast() && $task->status !== 'завершена' ? 'text-red-600 font-medium' : '' }}">
                                    {{ $task->dueDate->format('d.m.Y') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 