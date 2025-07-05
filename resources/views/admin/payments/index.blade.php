@extends('layouts.admin')

@section('content')
    <h1>Список платежей</h1>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">
                    #
                </th>
                <th scope="col">
                    Идентификатор
                </th>
                <th scope="col">
                    Номер заказа
                </th>
                <th scope="col">
                    Статус
                </th>
                <th scope="col">
                    Сумма, руб.
                </th>
                <th scope="col">
                    Подтвержден
                </th>
                <th scope="col">
                    Создан
                </th>
            </tr>
        </thead>
        
        <tbody>
            @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->getId() }}</td>
                    <td>{{ $payment->getUid() }}</td>
                    <td>{{ $payment->getOrderId() }}</td>
                    <td>
                        @php
                            $status = $payment->getStatus();
                            $statusName = match ($status) {
                                'succeeded' => 'Выполнен',
                                'pending' => 'Ожидает подтверждения',
                                'canceled' => 'Не прошел',
                                default => '-'
                            };
                        @endphp
                        {{ $statusName }}
                    </td>
                    <td>{{ $payment->getAmount() }}</td>
                    <td>{{ $payment->getConfirmedAt() ? date('d.m.Y H:i', strtotime($payment->getConfirmedAt())) : '-' }}</td>
                    <td>{{ $payment->getCreatedAt()->format('d.m.Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection