@extends('layouts.admin')

@section('title', 'Добавление новости')

@php
        /**

       * @var $categories
       */

       /**
       * @var $categories
       */
@endphp

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Добавление новости</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Вернуться к списку
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Заголовок <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Категория <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Выберите категорию</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="published_at" class="form-label">Дата публикации</label>
                        <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
                        @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if (isset($isAdmin) && $isAdmin)
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="user_id" class="form-label">Автор <span class="text-danger">*</span></label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">Выберите автора</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="content" class="form-label">Содержание <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('text') is-invalid @enderror" id="content" name="content" rows="10" required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="active" name="is_draft" value="1" {{ old('is_draft', '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_draft">
                                Черновик
                            </label>
                        </div>
                    </div>
                </div>
               {{-- <div class="mb-3">
                    <label for="image" class="form-label">Изображение</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Рекомендуемый размер: 800x450 пикселей. Максимальный размер: 2MB.</div>
                </div>--}}

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary me-md-2">Отмена</a>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Здесь можно добавить инициализацию редактора для поля content
        // Например, CKEditor, TinyMCE и т.д.
    });
</script>
@endsection
