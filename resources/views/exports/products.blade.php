<table>
    <thead>
    <tr>
        <th>№</th>
        <th>Название</th>
        <th>Категория</th>
        <th>Цена, руб.</th>
        <th>Количество на складе</th>
        <th>Создан</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->getId() }}</td>
            <td>{{ $product->getTitle() }}</td>
            <td>{{ $product->getCategory()->getTitle() }}</td>
            <td>{{ $product->getPrice() }}</td>
            <td>{{ $product->getStock() }}</td>
            <td>{{ $product->getCreatedAt()->format('d.m.Y H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>