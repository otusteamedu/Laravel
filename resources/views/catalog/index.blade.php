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
                    <a href="{{ route('catalog', $category->getId()) }}" class="btn btn-secondary rounded-pill me-3 mb-2">
                        {{ $category->getTitle() }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 mb-4">
                <form action="{{ route('catalog', $categoryId) }}" class="row row-cols-1 row-cols-sm-2 row-cols-lg-1 g-3">
                    <div>
                        <label class="text-success"></label>
                        <input class="form-control" type="search" name="q" value="{{ $req['q'] }}" placeholder="Текст"/>
                    </div>
                    
                    <div class="mt-3">
                        <label class="text-success">Цена</label>
                        <div class="d-flex">
                            <input type="number" name="min_price" placeholder="От" min="0" 
                                value="{{ $req['min_price'] }}" class="col form-control me-2">
                            <input type="number" name="max_price" placeholder="До" min="0" 
                                value="{{ $req['max_price'] }}" class="col form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="text-success">Бренды</label>
                        <div>
                            @foreach($brands as $brand)
                                <label class="me-1">
                                    <input type="checkbox" name="brands[]" value="{{ $brand->id }}" 
                                        @checked(in_array($brand->id, $req['brands']))>
                                    {{ $brand->title }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="text-success">Рейтинг</label>
                        <div>
                            <input type="number" name="rating" placeholder="От" min="0" max="10" step="0.01" 
                                value="{{ $req['rating'] }}" class="form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="text-success">Диагональ, дюймы</label>
                        <div class="d-flex">
                            <input type="number" name="min_screen" placeholder="От" min="5" max="7" step="0.1" 
                                value="{{ $req['min_screen'] }}" class="col form-control me-2">
                            <input type="number" name="max_screen" placeholder="До" min="5" max="7" step="0.1" 
                                value="{{ $req['max_screen'] }}" class="col form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="text-success">Оперативная память, GB</label>
                        <div class="d-flex">
                            <input type="number" name="min_ram" placeholder="От" min="2" max="8" step="1" 
                                value="{{ $req['min_ram'] }}" class="col form-control me-2">
                            <input type="number" name="max_ram" placeholder="До" min="2" max="8" step="1" 
                                value="{{ $req['max_ram'] }}" class="col form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="text-success">Встроенная память, GB</label>
                        <div class="d-flex">
                            <input type="number" name="min_builtin" placeholder="От" min="32" max="128" step="1" 
                                value="{{ $req['min_builtin'] }}" class="col form-control me-2">
                            <input type="number" name="max_builtin" placeholder="До" min="32" max="128" step="1" 
                                value="{{ $req['max_builtin'] }}" class="col form-control">
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-success mt-4" type="submit">Найти</button>
                    </div>
                </form>
            </div>

            <div class="col-lg-9">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xxl-3 g-3">
                    @foreach ($products as $product)
                        <div class="col">
                            <div class="card shadow-sm">
                                <a href="{{ route('product', $product->getId()) }}">
                                    @if ($product->getFirstImage())
                                        <img src="{{ asset('storage/' . $product->getFirstImage()->getAssetUrl()) }}" alt=""
                                            class="w-100">
                                    @else
                                        <img src="{{ asset('storage/uploads/placeholder.jpg') }}" alt="" class="w-100">
                                    @endif
                                </a>

                                <div class="card-body">
                                    <p class="card-text fs-5">
                                        <a href="{{ route('product', $product->getId()) }}"
                                            class="text-decoration-none text-dark">
                                            {{ $product->getTitle() }} ({{ $product->getBrand()->getTitle() }})
                                        </a>
                                    </p>

                                    <div class="fw-bold text-warning">
                                        {{ $product->getRating() }}
                                    </div>

                                    <div>
                                        <div class="d-flex justify-content-between">
                                            <span>Диагональ экрана</span> <span>{{ $product->getScreenSize() }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Оперативная память</span> <span>{{ $product->getRam() }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Встроенная память</span> <span>{{ $product->getBuiltinMemory() }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2">
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
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endunless
            </div>
        </div>
    </div>
@endsection