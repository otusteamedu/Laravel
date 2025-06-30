<h1>Новый заказ в Смартлайн</h1>
<p>Сделан заказ № {{ $order->getId() }}</p>
<p>Клиент: {{ $order->getUser()->name }} ({{ $order->getUser()->email }})</p>
<p>Сумма: {{ $order->getTotal() }} руб.</p>