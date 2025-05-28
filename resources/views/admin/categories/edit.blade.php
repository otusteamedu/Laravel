@extends('layouts.admin')

@section('content')
    <h1>Редактирование категории</h1>     

    <div class="row">
        @if ($errors->any())
        <div class="alert alert-danger mt-3 col-lg-8 col-xxl-6">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="row">
        <form class="mt-3 col-lg-8 col-xxl-6" action="{{ route('admin.categories.update', $categoryId) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="categoryTitle" class="form-label">Название категории</label>
                <input type="text" class="form-control" name="title" id="categoryTitle" value="{{ old('title') ?? $title }}">
            </div>
           
            <div class="mb-3">
                <label for="categoryDescription" class="form-label">Описание категории</label>
                <textarea class="form-control" id="categoryDescription" name="description" 
                    rows="6">{{ old('description') ?? $description }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection