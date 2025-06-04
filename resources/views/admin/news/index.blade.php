@extends('layouts.admin')

@section('title', 'Управление новостями')

@php
    /**
     * @var App\Services\News\Results\NewsItemsDTO[] $news
     */
@endphp
{{--@dd($news)--}}
@section('content')
    <div class="container-fluid px-0">
        <div class="row mb-4">
            <div class="col-md-6 mb-2 mb-md-0">
                <h1 class="h2">Управление новостями</h1>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Добавить новость
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if(count($news) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Заголовок</th>
                                <th class="d-none d-md-table-cell">Категория</th>
                                <th class="d-none d-lg-table-cell">Автор</th>
                                <th class="d-none d-md-table-cell">Черновик</th>
                                <th class="d-none d-md-table-cell">Дата публикации</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($news as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.news.edit', $item->id) }}"
                                           class="text-decoration-none fw-bold">
                                            {{ Str::limit($item->title, 50) }}
                                        </a>
                                        {{-- <div class="d-md-none mt-1">
                                             <small class="text-muted">{{ $item->category->name }}</small>
                                         </div>--}}
                                    </td>
                                    <td class="d-none d-md-table-cell">{{ $item->category->name }}</td>
                                    <td class="d-none d-lg-table-cell">{{ $item->author->name }}</td>

                                    <td class="d-none d-md-table-cell">{{ $item->isDraft ? 'Да' : 'Нет' }}</td>
                                    <td class="d-none d-md-table-cell">{{ $item->publishedAt->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.news.show', $item->id) }}"
                                               class="btn btn-outline-primary" target="_blank" title="Просмотр">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.news.edit', $item->id) }}"
                                               class="btn btn-outline-secondary" title="Редактировать">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Вы уверены? Это действие нельзя отменить.')">
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

                    {{--<div class="d-flex justify-content-center mt-4">
                        <nav>
                            {{ $news->appends(request()->query())->links() }}
                        </nav>
                    </div>--}}
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Новости не найдены. Создайте первую новость.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
