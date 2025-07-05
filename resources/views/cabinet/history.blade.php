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
                    <th scope="col">Товары</th>
                    <th scope="col">Статус</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->getId() }}</td>
                    <td>
                        {{ Illuminate\Support\Number::format($order->getTotal(), locale: 'ru') }}
                    </td>
                    <td>{{ $order->getCreatedAt()->format('d.m.Y H:i') }}</td>
                    <td>
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#collapse{{ $order->getId() }}" aria-expanded="false" aria-controls="collapse{{ $order->getId() }}">
                            Показать
                        </button>
                    </td>
                    <td @class([
                            'text-danger' => $order->getStatus() == 4,
                            'text-success' => $order->getStatus() == 3 || $order->getStatus() == 5,
                            'text-warning' => $order->getStatus() == 2,
                        ])>
                        {{ $order->getstatusName() }}
                    </td>
                    <td>
                        @if($order->getStatus() == 1 || $order->getStatus() == 4)
                        <a href="{{ route('pay', $order->getId()) }}" class="btn btn-success">Оплатить</a>
                        @endif
                    </td>
                </tr>
                <tr  class="collapse" id="collapse{{ $order->getId() }}">
                    <td colspan="6">
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