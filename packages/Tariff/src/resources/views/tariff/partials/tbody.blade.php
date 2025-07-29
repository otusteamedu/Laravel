@foreach($tariffs as $i => $tariff)
    <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $tariff->name }}</td>
        <td>{{ $tariff->maintenance }}</td>
        <td>{{ $tariff->heating_gcal }}</td>
        <td>{{ $tariff->heating_rub }}</td>
        <td>{{ $tariff->hot_water }}</td>
        <td>
            <a href="{{ route('tariffs.edit', $tariff->id) }}" class="btn btn-sm btn-warning">Редактировать</a>
            <a href="{{ route('tariffs.confirm_delete', $tariff->id) }}" class="btn btn-sm btn-danger">Удалить</a>
        </td>
    </tr>
@endforeach
