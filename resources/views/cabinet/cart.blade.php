@extends('layouts.app')

@section('content')
<div class="bg-body-tertiary rounded-3">
    <div class="container py-3">
        <h1 class="display-5 fw-bold text-center">Корзина</h1>
    </div>
</div>

<div class="container mb-5" data-lat="{{ $lat }}" data-lon="{{ $lon }}">
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
            @if (empty($cart))
                <p>Ваша корзина пуста</p>
            @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Название</th>
                        <th scope="col">Кол-во</th>
                        <th scope="col">Цена, руб.</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $productId => $item)
                    <tr>
                        <td>{{ $item['product'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ Illuminate\Support\Number::format($item['price'], locale: 'ru') }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $productId) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn">
                                    <img src="{{ asset('images/trash.png') }}" alt="" width="20">
                                </button>
                            </form>
                        </td>
                    </tr>  
                    @endforeach
                </tbody>
            </table>

            <p class="fw-bold fs-5">Общая стоимость: {{ Illuminate\Support\Number::format($total, locale: 'ru') }} руб.</p>

            <div class="d-flex align-items-start">
                <a href="{{ route('cart.order') }}" class="btn btn-success">Оформить заказ</a>
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger mb-4 ms-1">Очистить корзину</button>
                </form>
            </div>
            
            @endif

            <div>
                <div class="row">
                    <div class="col-12 col-md-2 mb-1">Доставка</div>

                    <div class="col-12 col-md-5 mb-1 d-flex">
                        <div class="form-check ms-3">
                            <input class="form-check-input" type="radio" name="delivery" id="pickup" value="pickup" 
                                @checked(empty($address)) @disabled(empty($cart))>
                            <label class="form-check-label" for="pickup">Самовывоз</label>
                        </div>
                        <div class="form-check ms-3">
                            <input class="form-check-input" type="radio" name="delivery" id="curier" value="curier"
                                @checked(!empty($address)) @disabled(empty($cart))>
                            <label class="form-check-label" for="curier">Курьером по адресу</label>
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-5 mb-1">
                        <input type="text" class="form-control" placeholder="Адрес доставки" id="address" name="address" 
                            value="{{ $address }}" @disabled(empty($address))>
                    </div>
                </div>
            </div>

            <div id="map" class="w-100 mt-4 mb-1"></div>
        </div>
    </div>
</div>
@endsection