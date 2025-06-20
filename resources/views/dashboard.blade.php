@extends('layouts.public')

@section('title', 'Дашборд')

@section('content')
<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Добро пожаловать!</h1>
                <p class="text-gray-600 mt-1">Это ваш дашборд. Здесь вы найдете краткую информацию о задачах и активности.</p>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="p-6 border-b border-gray-200">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-lg p-4 text-center border border-blue-200">
                <div class="text-2xl font-bold text-gray-900">{{ $tasksTotal ?? 0 }}</div>
                <div class="text-sm text-gray-600">Всего задач</div>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $tasksNew ?? 0 }}</div>
                <div class="text-sm text-gray-600">Новые</div>
            </div>
            <div class="bg-orange-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-orange-600">{{ $tasksInProgress ?? 0 }}</div>
                <div class="text-sm text-gray-600">В работе</div>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $tasksDone ?? 0 }}</div>
                <div class="text-sm text-gray-600">Завершенные</div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="p-6">
        <div class="flex space-x-4">
            <a href="{{ route('tasks.index') }}" class="btn-primary">Перейти к задачам</a>
            <a href="{{ route('tasks.create') }}" class="btn-secondary">Создать задачу</a>
        </div>
    </div>
</div>
@endsection 