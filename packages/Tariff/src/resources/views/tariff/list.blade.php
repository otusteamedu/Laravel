@foreach ($tariff_list as $index => $tariff)
<tr>
  <td>
    <span class="apartment-nomer">{{ $loop->iteration }}</span>
  </td>
  <td style="padding-left: 10px">{{ $tariff->name }}</td>
  <td>{{ $tariff->maintenance }}</td>
  <td>{{ $tariff->heating }}</td>
  <td>{{ $tariff->heating_rub }}</td>
  <td>{{ $tariff->hot_water }}</td>
  <td>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-5">
          <button 
            class="edit-btn2" 
            hx-get="{{ route('tariff.edit', ['tariff' => $tariff->id]) }}" 
            hx-target="#dialog">
            Редактировать
          </button>
        </div>
        <div class="col-5">
          <button 
            class="delete-btn2" 
            hx-get="{{ route('tariff.delete', ['tariff' => $tariff->id]) }}" 
            hx-target="#confirmation">
            Удалить
          </button>
        </div>
      </div>
    </div>
  </td>
</tr>
@endforeach
