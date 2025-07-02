@extends('layouts.admin')

@section('title', $news->title)

@php
    /**
     * @var \App\Services\DTO\News\NewsDTO $news
     */
@endphp

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Просмотр новости</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> К списку
            </a>
            <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Редактировать
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title h5 mb-0">{{ $news->title }}</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Категория:</strong> {{ $news->category->name }}</p>
                    <p class="mb-1"><strong>Автор:</strong> {{ $news->author->name }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Создано:</strong> {{ $news->createdAt->format('d.m.Y H:i') }}</p>
                    <p class="mb-1"><strong>Обновлено:</strong> {{ $news->updatedAt->format('d.m.Y H:i') }}</p>
                    <p class="mb-1"><strong>Дата публикации:</strong> {{ $news->publishedAt->format('d.m.Y H:i') }}</p>
                </div>
            </div>

            <div class="mb-4">
                <h4 class="h6">Содержание:</h4>
                <div class="content-box p-3 border rounded">
                    {!! $news->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
