@extends('layouts.app')

@section('content')
<div class="container-lg">
    <h1 class="display-6 fw-bold text-center mt-4">{{ $title }} ({{ $brand }})</h1>

    <div class="d-flex flex-column flex-md-row justify-content-evenly mt-4">
        <div class="fw-bold fs-3">{{ ucfirst($categoryTitle) }}</div>
        <div class="fw-bold fs-4">{{ $price }} руб.</div>
        <div>
            <a href="{{ route('catalog') }}" class="btn btn-link">Каталог</a>
        </div>
        <form action="{{ route('cart.add', $productId) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm fs-5">Добавить в корзину</button>
        </form>
    </div> 

    <div class="row">
        <p class="fs-5 mt-4 mx-auto col-xl-8 fw-bold text-warning">{{ $rating }}</p>
    </div>
    
    <div class="row">
        <p class="fs-5 mt-4 mx-auto col-xl-8 fw-bold">
            Диагональ экрана {{ $screenSize }}<br>
            Оперативная память {{ $ram }}<br>
            Встроенная память {{ $builtinMemory }}<br>
        </p>
    </div>

    <div class="row">
        <p class="fs-5 mt-4 mx-auto col-xl-8">{{ $description }}</p>
    </div>

    @if ($assets->count() > 0)
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
@endsection