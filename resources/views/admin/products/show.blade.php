@extends('layouts.admin')

@section('content')
    <h1>Просмотр телефона</h1>

    <div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-link">Вернуться</a>
    </div>

    <div class="row mt-3">
        <div class="card col-xl-8">
            <div class="card-body">
                <h4 class="card-title fw-bold">{{ $title }}</h4>
                <h6 class="card-subtitle mb-2 text-body-secondary">Создан: {{ $createdAt }}</h6>
                <h6 class="card-subtitle mb-2 text-body-secondary">Обновлен: {{ $updatedAt }}</h6>
                <p class="card-text">{{ $description }}</p>

                <div class="row">
                    <ul class="list-group list-group-flush col-xl-6">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                Категория
                                <div class="fw-bold">{{ $categoryTitle }}</div>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                Цена, руб.
                                <div class="fw-bold">{{ $price }}</div>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                Количество на складе
                                <div class="fw-bold">{{ $stock }}</div>
                            </div>
                        </li>
                    </ul>
                </div>

                @if ($assets->count() > 0)
                <div class="my-3">
                    <h5 class="fw-bold">Изображения/видео: {{ $assets->count() }}</h5>
                </div>

                <div id="carousel" class="carousel carousel-dark slide mt-4" style="height:50vh">
                    <div class="carousel-inner h-100">
                    @foreach ($assets as $asset)
                        @if ($asset->getType() == 'image')
                        <div class="carousel-item h-100 @if ($loop->first) active @endif">
                            <img src="{{ asset('storage/' . $asset->getAssetUrl()) }}" class="d-block object-fit-contain w-100 h-100" alt="">
                        </div>
                        @endif
                        @if ($asset->getType() == 'video')
                        <div class="carousel-item h-100 @if ($loop->first) active @endif">
                            <video controls muted class="d-block object-fit-contain mx-auto w-75 h-100">
                                <source src="{{ asset('storage/' . $asset->getAssetUrl()) }}" type="video/mp4">
                            </video>
                        </div>
                        @endif
                    @endforeach
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection