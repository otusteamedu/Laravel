@extends('layouts.admin')

@php
    /**
     * @var App\Services\Category\Results\CategoryDTO $category
     */
@endphp

@section('title', 'Редактирование категории')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Редактирование категории</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Вернуться к списку
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Название <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Слаг</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" readonly>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Автоматическая генерации из названия.</div>
                </div>

                <div class="mb-3">
                    <label for="sort" class="form-label">Сортировка</label>
                    <input type="text" class="form-control @error('sort') is-invalid @enderror" id="sort" name="sort" value="{{ old('sort', $category->sort) }}"/>
                    @error('sort')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <div>
                        <span class="text-muted">ID: {{ $category->id }}</span>
                    </div>
                    <div>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">Отмена</a>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
{{--<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const originalSlug = "{{ $category->slug }}";

        nameInput.addEventListener('input', function() {
            // Только если slug пуст или равен оригинальному слагу, сгенерированному из прежнего названия
            if (!slugInput.value || slugInput.value === originalSlug) {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
            }
        });
    });
</script>--}}
@endsection
