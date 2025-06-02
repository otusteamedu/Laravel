@extends('layouts.app')

@section('content')
<div class="bg-body-tertiary rounded-3">
    <div class="container pt-4 pb-5">
        <h1 class="display-5 fw-bold text-center">История заказов</h1>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Стоимость, руб.</th>
                    <th scope="col">Создан</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->getId() }}</td>
                    <td class="d-flex">
                        {{ Illuminate\Support\Number::format($order->getTotal(), locale: 'ru') }}
                        <button class="btn btn-outline-secondary btn-sm ms-3" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#collapse{{ $order->getId() }}" aria-expanded="false" aria-controls="collapse{{ $order->getId() }}">
                            Товары
                        </button>
                    </td>
                    <td>{{ $order->getCreatedAt()->format('d.m.Y H:i') }}</td>
                </tr>
                <tr  class="collapse" id="collapse{{ $order->getId() }}">
                    <td colspan="3">
                        <table class="table table-borderless">
                            <tbody>
                                @foreach ($order->getProducts() as $product)
                                <tr>
                                    <td>{{ $product->getTitle() }}</td>
                                    <td>{{ $product->pivot->count }}</td>
                                    <td>{{ Illuminate\Support\Number::format($product->pivot->paid_price, locale: 'ru') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection