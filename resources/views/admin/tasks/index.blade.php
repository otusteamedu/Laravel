@extends('layouts.admin')

@section('title', 'Управление задачами')

@section('header', 'Управление задачами')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Список задач</h5>
        <a href="{{ route('admin.tasks.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> Добавить задачу
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Название</th>
                        <th>Пользователь</th>
                        <th>Категория</th>
                        <th>Статус</th>
                        <th>Срок</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td>{{ $task->id }}</td>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->executorName ?: 'Не назначен' }}</td>
                            <td>
                                @if($task->categoryName)
                                        {{ $task->categoryName }}
                                @else
                                    Без категории
                                @endif
                            </td>
                            <td>
                                @if($task->status === 'новая')
                                    <span class="badge bg-primary">{{ $task->status }}</span>
                                @elseif($task->status === 'в работе')
                                    <span class="badge bg-info">{{ $task->status }}</span>
                                @elseif($task->status === 'выполнена')
                                    <span class="badge bg-success">{{ $task->status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $task->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($task->dueDate)
                                    {{ $task->dueDate->format('d.m.Y') }}
                                    @if($task->dueDate < now() && $task->status !== 'выполнена')
                                        <span class="badge bg-danger">Просрочена</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary rounded me-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST"
                                          onsubmit="return confirm('Вы уверены, что хотите удалить эту задачу?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Задач пока нет</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $tasks->links() }}
        </div>
    </div>
</div>
@endsection
