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
                    <td>{{ $payment->id }}</td>
                    <td>{{ $payment->uid }}</td>
                    <td>{{ $payment->order_id }}</td>
                    <td>{{ $payment->status->message() }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ $payment->confirmed_at ? $payment->confirmed_at->format('d.m.Y H:i') : '-' }}</td>
                    <td>{{ $payment->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection