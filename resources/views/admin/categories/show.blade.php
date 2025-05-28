@extends('layouts.admin')

@section('content')
    <h1>Просмотр категории</h1>  
    
    <div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-link">Вернуться</a>
    </div>

    <div class="row mt-3">
        <div class="card col-xl-8">
            <div class="card-body">
                <h5 class="card-title fw-bold">{{ $title }}</h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Создана: {{ $createdAt }}</h6>
                <p class="card-text">{{ $description }}</p>
            </div>
        </div>
    </div>
@endsection