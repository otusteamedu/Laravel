@extends('layouts.admin')

@section('content')
    <h1>Просмотр заказа</h1>

    <div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-link">Вернуться</a>
    </div>

    <div class="row mt-3">
        <div class="card col-xl-8">
            <div class="card-body">
                <h4 class="card-title fw-bold">№ {{ $orderId }}</h4>
                <h6 class="card-subtitle mb-2 text-body-secondary">Создан: {{ $createdAt }}</h6>
                <h6 class="card-subtitle mb-2 text-body-secondary">Обновлен: {{ $updatedAt }}</h6>

                <div class="card-text">
                    <div class="fw-bold">Клиент</div>
                    <div>{{ $clientName }} - {{ $clientEmail }}</div>
                </div>

                <div class="fw-bold mt-4">Товары</div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Название</th>
                        <th scope="col">Количество</th>
                        <th scope="col">Цена, руб. </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->getTitle() }}</td>
                            <td>{{ $product->pivot->count }}</td>
                            <td>{{ Illuminate\Support\Number::format($product->pivot->paid_price, locale: 'ru') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="my-3">
                    <h5 class="fw-bold">Полная стоимость: {{ Illuminate\Support\Number::format($total, locale: 'ru') }} руб.</h5>
                </div>
            </div>
        </div>
    </div>
@endsection