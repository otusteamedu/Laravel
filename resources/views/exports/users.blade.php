<table>
    <thead>
    <tr>
        <th>№</th>
        <th>Имя</th>
        <th>Email</th>
        <th>Роль</th>
        <th>Создан</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role->getTitle() }}</td>
            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>