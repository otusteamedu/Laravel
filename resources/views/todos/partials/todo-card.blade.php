@php
/**
 * @var \App\Services\Repositories\DTOs\ProjectDTO $project
 * @var \App\Services\Repositories\Todo\TodoFetchDTO $todo
 */
@endphp
<div class="col-12 card mb-3" style="border-color: {{ $todo->status->color }}">
    <div class="card-header d-flex" style="background-color: {{ $todo->status->color }}">
        <div class="fw-bold">#{{ $todo->todoId }} - {{ $todo->title }}</div>
        <div class="ms-auto">{{ $todo->status->name }}</div>
        @if (!empty($todo->options['isHot']))
            <i class="fa-solid fa-fire text-danger ms-2" title="Важная"></i>
        @endif
    </div>
    <div class="card-body">
        <p class="card-text">{!! $todo->description !!}</p>
        <p class="card-text"><small class="text-muted">Крайний срок {{ $todo->deadline->format("d.m.Y") }}</small></p>
    </div>
    <div class="card-footer">
        <p class="card-text d-flex flex-wrap justify-content-end gap-3">
            <span class="text-muted">
                <i class="fa-solid fa-eye"></i>
                <a href="{{ route('project.todos.show', ['projectId' => $project->projectId, 'todoId' => $todo->todoId]) }}"
                    class="btn p-0"
                    >Подробнее</a>
            </span>
        </p>
    </div>
</div>
