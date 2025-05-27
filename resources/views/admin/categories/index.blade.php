
@extends('layouts.admin')

@section('title', 'Управление категориями')

@php
    /**
     * @var App\Services\Category\Results\Result[] $categories
     */
@endphp

@section('content')
<div class="container-fluid px-0">
    <div class="row mb-4">
        <div class="col-md-6 mb-2 mb-md-0">
            <h1 class="h2">Управление категориями</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Добавить категорию
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if(count($categories) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Название</th>
                                <th class="d-none d-md-table-cell">Слаг</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-decoration-none fw-bold">
                                            {{ $category->name }}
                                        </a>
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $category->slug }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-outline-primary" target="_blank" title="Просмотр">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-secondary" title="Редактировать">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Вы уверены? Это действие может затронуть связанные новости.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Удалить">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

               {{-- <div class="d-flex justify-content-center mt-4">
                    <nav>
                        {{ $categories->appends(request()->query())->links() }}
                    </nav>
                </div>--}}
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Категории не найдены. Создайте первую категорию.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
