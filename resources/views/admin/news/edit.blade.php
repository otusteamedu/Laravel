@extends('layouts.admin')

@section('title', 'Редактирование новости')

@php
    /**
     * @var \App\Services\DTO\News\NewsDTO $news
     */

    /**
     * @var \App\Application\UseCases\Category\DTO\CategoryDTO $category
     */
@endphp

@section('content')

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1>Редактирование новости</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i> К списку
                </a>
                <a href="{{ route('admin.news.show', $news->id) }}" class="btn btn-info">
                    <i class="fas fa-eye me-1"></i> Просмотр
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Заголовок <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                               name="title" value="{{ old('title', $news->title) }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Категория <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category->id }}" {{ old('category_id', $news->category->id) == $category->id ? 'selected' : '' }}>
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
                            <input type="datetime-local"
                                   class="form-control @error('published_at') is-invalid @enderror" id="input_published_at"
                                   name="published_at"
                                   value="{{ old('published_at', $news->publishedAt?->format('Y-m-d\TH:i')) }}">
                            @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if (isset($isAdmin) && $isAdmin)
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="user_id" class="form-label">Автор <span class="text-danger">*</span></label>
                                <select class="form-select @error('user_id') is-invalid @enderror" id="user_id"
                                        name="user_id" required>
                                    <option value="">Выберите автора</option>
                                    @foreach($users as $user)
                                        <option
                                            value="{{ $user->id }}" {{ old('user_id', $news->author->id) == $user->id ? 'selected' : '' }}>
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
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content"
                                  name="content" rows="10" required>{{ old('content', $news->content) }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkbox_is_draft" name="is_draft"
                                       value="1" {{ old('is_draft', $news->isDraft) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_draft">
                                    Черновик
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <span
                                class="text-muted">ID: {{ $news->id }} | Создано: {{ $news->createdAt->format('d.m.Y H:i') }}</span>
                        </div>
                        <div>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary me-2">Отмена</a>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Здесь можно добавить инициализацию редактора для поля content
            // Например, CKEditor, TinyMCE и т.д.

            const checkboxIsDraft = document.getElementById('checkbox_is_draft');
            const inputPublishedAt = document.getElementById('input_published_at');
            isDraftHandler();
            checkboxIsDraft.addEventListener('change', isDraftHandler);

            function isDraftHandler() {
                if (checkboxIsDraft.checked) {
                    inputPublishedAt.value = '';
                    inputPublishedAt.disabled = true;
                } else {
                    inputPublishedAt.disabled = false;
                    inputPublishedAt.value = formatDateYmdHi();
                }
            }
        });

        function formatDateYmdHi(date = new Date()) {
            const pad = (n) => String(n).padStart(2, '0');

            const year = date.getFullYear();
            const month = pad(date.getMonth() + 1); // Месяцы от 0 до 11
            const day = pad(date.getDate());
            const hours = pad(date.getHours());
            const minutes = pad(date.getMinutes());

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }
    </script>
@endsection
