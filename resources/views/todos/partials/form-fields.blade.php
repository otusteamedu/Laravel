@php
/**
 * @var \App\Services\Repositories\Todo\TodoFetchDTO $todo
 * @var \Illuminate\Support\ViewErrorBag $errors
 */
@endphp
<div class="col-12 mb-3">
    <input class="form-check-input" name="options[isHot]" type="checkbox" id="todo-options-hot"
        @checked(old('options[isHot]', !empty($todo->options['isHot'])))
    >
    <label for="todo-options-hot" class="form-label">Важная</label>
</div>

<div class="col-12 mb-3">
    <label for="todo-title" class="form-label">Название</label>
    <input id="todo-title" name="title" type="text"
        value="{{ old('title', $todo->title ?? '') }}"
        @class([
            'form-control',
            'is-invalid' => !empty($errors->get('title'))
        ]) 
        placeholder="Укажите название задачи" required autocomplete="off">
    <x-invalid-feedback :errors="$errors->get('title')"/>
</div>

<div class="col-12 mb-3">
    <label for="todo-description" class="form-label">Oписание задачи</label>
    <textarea id="todo-description" name="description" rows="5"
        @class([
            'form-control',
            'is-invalid' => !empty($errors->get('description'))
        ]) 
        >{{ old('description', $todo->description ?? '') }}</textarea>
    <x-invalid-feedback :errors="$errors->get('description')"/>
</div>

<div class="col-12 mb-3">
    <label for="todo-deadline" class="form-label">Крайний срок</label>
    <input id="todo-deadline" name="deadline" type="date"
            @class([
            'form-control',
            'is-invalid' => !empty($errors->get('deadline'))
        ]) 
        value={{ old('deadline', $todo->deadline->format("Y-m-d") ?? now()->format("Y-m-d")) }}
    >
    <x-invalid-feedback :errors="$errors->get('deadline')"/>
</div>

<div class="col-12 mb-3">
    <div class="input-group">
        <span class="input-group-text"><i class="fa-brands fa-youtube"></i></span>
        <input id="todo-options-video" name="options[video]" type="text"
            value="{{ old('options[video]', $todo->options['video'] ?? '') }}"
            @class([
                'form-control',
                'is-invalid' => !empty($errors->get('options.video'))
            ]) 
            placeholder="Укажите ссылку на видео" autocomplete="off"
        >
        <x-invalid-feedback :errors="$errors->get('options.video')"/>
    </div>
    <small id="todo-options-video-help" class="form-text text-muted">Вставьте ссылку на видео с YouTube, Rutube или VKVideo</small>
</div>
