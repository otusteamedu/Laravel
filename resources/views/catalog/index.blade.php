@extends('layouts.app')

@section('content')
    <div class="container-lg">
        <div class="p-3 bg-body-tertiary rounded-3">
            <div>
                <h1 class="display-6 fw-bold text-center">
                    Каталог 
                    @if($currentCategory)
                    - {{ $currentCategory->getTitle() }}
                    @endif
                </h1>
            </div>
            <div class="d-flex flex-wrap mt-3">
                @foreach ($categories as $category)
                <a href="{{ route('category', $category->getId()) }}" class="btn btn-secondary rounded-pill me-3 mb-2">
                    {{ $category->getTitle() }}
                </a>
                @endforeach
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            @foreach ($products as $product)
            <div class="col">
                <div class="card shadow-sm">
                    <a href="{{ route('product', $product->getId()) }}">
                        @if ($product->getFirstImage())
                        <img src="{{ asset('storage/' . $product->getFirstImage()->getAssetUrl()) }}" alt="" class="w-100">
                        @else
                        <img src="{{ asset('storage/uploads/placeholder.jpg') }}" alt="" class="w-100">
                        @endif
                    </a>

                    <div class="card-body">
                        <p class="card-text fs-5">
                            <a href="{{ route('product', $product->getId()) }}" class="text-decoration-none text-dark">
                                {{ $product->getTitle() }}
                            </a>
                        </p>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex justify-content-between align-items-center text-body-secondary fs-5">
                                {{ Illuminate\Support\Number::format($product->getPrice(), locale: 'ru') }} руб.
                            </div>
                            <form action="{{ route('cart.add', $product->getId()) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">Добавить в корзину</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @unless($currentCategory)
        <div class="mt-4">
            {{ $products->links() }}
        </div>
        @endunless
    </div>
@endsection