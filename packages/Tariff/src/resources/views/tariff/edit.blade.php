@extends('layouts.base')

@section('content')
@php
    $isNew = empty($tariff->id);
    $actionUrl = $isNew ? route('tariffs.store') : route('tariffs.update', $tariff->id);
@endphp

<form 
    hx-post="{{ $actionUrl }}" 
    hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}' 
    class="modal-content"
>
    {{-- Метод PUT для редактирования --}}
    @if (!$isNew)
        @method('PUT')
    @endif

    <div class="modal-content">
        <div class="modal-header text-center custom-modal-header">
            <h5 class="modal-title w-100" style="font-size: 14px; color: #FFFFFF; font-family: Segoe UI,serif; font-weight: 400">
                {{ $title ?? ($isNew ? 'Создание нового тарифа' : 'Редактирование тарифа') }}
            </h5>
        </div>

        <div class="modal-body">
            @php
                $fields = [
                    ['name', 'Название'],
                    ['hot_water_odn', 'Горячая вода (ОДН)'],
                    ['solid_waste', 'Обращение с ТКО'],
                    ['maintenance', 'Содержание жилья'],
                    ['cold_water', 'Холодная вода'],
                    ['electricity', 'Электроэнергия'],
                    ['heating', 'Отопление (Гкал)'],
                    ['cold_water_odn', 'Холодная вода (ОДН)'],
                    ['electricity_odn', 'Электроэнергия (ОДН)'],
                    ['heating_rub', 'Отопление (Руб)'],
                    ['sewage', 'Водоотведение'],
                    ['lift', 'Лифт'],
                    ['hot_water', 'Горячая вода'],
                    ['sewage_odn', 'Канализация (ОДН)'],
                    ['capital_repair', 'Капитальный ремонт'],
                    ['multiplying_factor', 'Размер повышающего коэффициента']
                ];
            @endphp

            @foreach(array_chunk($fields, 3) as $row)
                <div class="row mb-3">
                    @foreach($row as [$name, $label])
                        <div class="col">
                            <label for="{{ $name }}" class="form-label">{{ $label }}:</label>
                            <input 
                                type="text" 
                                name="{{ $name }}" 
                                id="{{ $name }}" 
                                value="{{ old($name, $tariff->$name ?? '') }}" 
                                class="form-control @error($name) is-invalid @enderror"
                            >
                            @error($name)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
            @endforeach

            <br>

            <div class="row mb-3">
                <div class="col text-center">
                    <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                        <a href="javascript:void(0)" class="btn-segoe" data-bs-dismiss="modal">Отменить</a>
                        <a href="javascript:void(0)" class="btn-segoe-primary" id="saveTariffButton">Сохранить</a>
                        <input type="submit" class="d-none" id="submitButtonSaveTariff">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.getElementById("saveTariffButton").addEventListener("click", function() {
        document.getElementById("submitButtonSaveTariff").click();
    });
</script>
@endsection
