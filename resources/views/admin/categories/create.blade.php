@extends('layouts.admin')

@section('title', 'Добавление категории')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Добавление категории</h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Вернуться к списку
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">
                            Активна
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Название <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="sort" class="form-label">Сортировка</label>
                    <input type="text" class="form-control @error('sort') is-invalid @enderror" id="sort" name="sort" value="{{ old('sort', 1) }}"/>
                    @error('sort')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-md-2">Отмена</a>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
/*    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        nameInput.addEventListener('input', function() {
            if (!slugInput.value) {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
            }
        });
    });*/
</script>
@endsection
