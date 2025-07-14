@extends('layouts.app')

@section('content')
    <div class="container-lg">
        <h1 class="display-6 fw-bold text-center mt-4">
            Результаты поиска
        </h1>

        <div class="row">
            <div class="mx-auto col-md-8 col-xl-6 fs-5">
                @foreach ($products as $product)
                    <p><a href="{{ route('product', $product->getId()) }}">{{ $product->getTitle() }}</a></p>
                @endforeach
            </div>
        </div>
    </div>
@endsection