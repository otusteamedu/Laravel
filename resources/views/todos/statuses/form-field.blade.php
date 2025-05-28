@php
/**
 * @var string|null $name
 * @var string|null $description
 * @var \Illuminate\Support\ViewErrorBag $errors
 */
@endphp
<div x-data class="d-flex col-12 flex-wrap">
    <div class="col-12 mb-3">
        <label for="status-name" class="form-label">Название</label>
        <input id="status-name" name="name" type="text"
            x-model="$store.todoStatuses.name"
            @class([
                'form-control',
                'is-invalid' => !empty($errors->get('name'))
            ]) 
            placeholder="Укажите название статуса" required autocomplete="off">
        <x-invalid-feedback :errors="$errors->get('name')"/>
    </div>
    <div class="col-12 col-sm-5 mb-3 me-auto">
        <label for="status-color" class="form-label">Цвет</label>
        <input id="status-color" name="color" type="color"
            x-model="$store.todoStatuses.color"
            @class([
                'form-control',
                'is-invalid' => !empty($errors->get('color'))
            ]) 
            required autocomplete="false">
        <x-invalid-feedback :errors="$errors->get('color')"/>
    </div>

    <div class="col-12 col-sm-5 mb-3 ms-auto">
        <label for="status-sort" class="form-label">Порядок сортировки</label>
        <input id="status-sort" name="sort" type="number"
            x-model="$store.todoStatuses.sort"
            @class([
                'form-control',
                'is-invalid' => !empty($errors->get('sort'))
            ]) 
            required autocomplete="false">
        <x-invalid-feedback :errors="$errors->get('sort')"/>
    </div>
</div>