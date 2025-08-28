<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Подтверждение заказа #{{ $orderNumber }}</title>
</head>
<body>
<h2>Благодарим за ваш заказ!</h2>

<p>Уважаемый(ая) {{ $customerName }},</p>

<p>Ваш заказ <strong>#{{ $orderNumber }}</strong> успешно создан и находится в обработке.</p>

<h3>Детали заказа:</h3>
<ul>
    <li><strong>Номер заказа:</strong> #{{ $orderNumber }}</li>
    <li><strong>Дата заказа:</strong> {{ $orderDate }}</li>
    <li><strong>Общая сумма:</strong> {{ $totalAmount }} руб.</li>
    <li><strong>Статус:</strong> В обработке</li>
</ul>

<h3>Состав заказа:</h3>
<table style="width: 100%; border-collapse: collapse;">
    <thead>
    <tr>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Товар</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Количество</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Цена</th>
        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Сумма</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->getItems() as $item)
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px;">Товар #{{ $item->getProductId() }}</td>
            <td style="border: 1px solid #ddd; padding: 8px;">{{ $item->getQuantity() }}</td>
            <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($item->getPrice(), 2, '.', ' ') }} руб.</td>
            <td style="border: 1px solid #ddd; padding: 8px;">{{ number_format($item->getTotal(), 2, '.', ' ') }} руб.</td>
        </tr>
    @endforeach
    </tbody>
</table>

@if($order->getShippingAddress())
    <h3>Адрес доставки:</h3>
    <p>{{ $order->getShippingAddress() }}</p>
@endif

<p>Мы свяжемся с вами в ближайшее время для подтверждения заказа.</p>

<p>С уважением,<br>Команда {{ config('app.name') }}</p>
</body>
</html>
