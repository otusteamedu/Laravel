@extends('layouts.admin')

@section('content')
    <h1>Создание нового телефона</h1>     

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
        <form class="mt-3 col-lg-8 col-xxl-6" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="productTitle" class="form-label">Название телефона</label>
                <input type="text" class="form-control" name="title" id="productTitle" value="{{ old('title') }}">
            </div>
           
            <div class="mb-3">
                <label for="productDescription" class="form-label">Описание телефона</label>
                <textarea class="form-control" id="productDescription" name="description" 
                    rows="6">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="productCategory" class="form-label">Категория</label>
                <select class="form-select" name="category_id" id="productCategory">
                    @foreach ($categories as $category)
                    <option value="{{ $category->getId() }}" @selected(old('category_id') == $category->getId())>
                        {{ $category->getTitle() }}   
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="productPrice" class="form-label">Цена</label>
                <input type="text" class="form-control" name="price" id="productPrice" value="{{ old('price') }}">
            </div>

            <div class="mb-3">
                <label for="productStock" class="form-label">Количество на складе</label>
                <input type="text" class="form-control" name="stock" id="productStock" value="{{ old('stock') }}">
            </div>

            <div class="mb-3">
                <label for="productAssets" class="form-label">Изображения/видео</label>
                <input class="form-control" type="file" name="assets[]" id="productAssets" multiple>
            </div>

            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection