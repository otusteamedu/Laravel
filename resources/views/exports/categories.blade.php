<table>
    <thead>
    <tr>
        <th>№</th>
        <th>Название</th>
        <th>Создана</th>
    </tr>
    </thead>
    <tbody>
    @foreach($categories as $category)
        <tr>
            <td>{{ $category->getId() }}</td>
            <td>{{ $category->getTitle() }}</td>
            <td>{{ $category->getCreatedAt()->format('d.m.Y H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>