<table>
    <thead>
    <tr>
        <th>№</th>
        <th>Клиент</th>
        <th>Полная стоимость</th>
        <th>Создан</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->getId() }}</td>
            <td>{{ $order->getUser()->name }}</td>
            <td>{{ $order->getTotal() }}</td>
            <td>{{ $order->getCreatedAt()->format('d.m.Y H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>