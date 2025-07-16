@php
/**
 * @var string|null $name
 * @var string|null $description
 * @var \Illuminate\Support\ViewErrorBag $errors
 */
@endphp
<div class="col-12 mb-3">
    <label for="project-name" class="form-label">Название</label>
    <input id="project-name" name="name" type="text"
        value="{{ old('name', $name ?? '') }}"
        @class([
            'form-control',
            'is-invalid' => !empty($errors->get('name'))
        ]) 
        placeholder="Укажите название проекта" required autocomplete="off">
    <x-todo-app::invalid-feedback :errors="$errors->get('name')"></x-todo-app::invalid-feedback>
</div>

<div class="col-12 mb-3">
    <label for="project-description" class="form-label">Краткое описание</label>
    <textarea id="project-description" name="description" rows="5"
        @class([
            'form-control',
            'is-invalid' => !empty($errors->get('description'))
        ]) 
        >{{ old('description', $description ?? '') }}</textarea>
    <x-todo-app::invalid-feedback :errors="$errors->get('description')"></x-todo-app::invalid-feedback>
</div>
