@extends('layouts.app')

@section('content')
<div class="bg-body-tertiary rounded-3">
    <div class="container py-3">
        <h1 class="display-5 fw-bold text-center">Оформление заказа</h1>
    </div>
</div>

<div class="container mb-5">
    <div>
        @if (session('success'))
        <div class="alert alert-success mt-3">
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger mt-3">
            <span>{{ session('error') }}</span>
        </div>
        @endif
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Название</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена, руб.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $productId => $item)
                    <tr>
                        <td>{{ $item['product'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ Illuminate\Support\Number::format($item['price'], locale: 'ru') }}</td>
                    </tr>  
                    @endforeach
                </tbody>
            </table>

            <p>Доставка: {{ $address }}</p>

            <p class="fs-5">Стоимость товаров: {{ Illuminate\Support\Number::format($total, locale: 'ru') }} руб.</p>
            <p class="fs-5">Стоимость доставки: {{ Illuminate\Support\Number::format($deliveryPrice, locale: 'ru') }} руб.</p>
            <p class="fw-bold fs-5">Полная стоимость заказа: {{ Illuminate\Support\Number::format($totalPrice, locale: 'ru') }} руб.</p>

            <div class="d-flex align-items-start">
                <a href="{{ route('cart.confirm') }}" class="btn btn-success">Подтвердить заказ</a>
                <a href="{{ route('cart.index') }}" class="btn btn-secondary ms-1">Вернуться в корзину</a>
            </div>
        </div>
    </div>
</div>
@endsection