@php
/**
 * @var int    $projectId
 * @var string $name
 * @var string $description
 * @var string $created
 */
@endphp
<div class="card mb-3">
    <div class="card-header fw-bold">
        {{ $name }}
    </div>
    <div class="card-body">
        <p class="card-text">{{ $description }}</p>
        <p class="card-text"><span class="text-muted">Создан: {{ $created }}</span></p>

        <p class="card-text d-flex flex-wrap justify-content-end gap-3">
            <span class="text-muted">
                <i class="fa-solid fa-eye"></i>
                <a href="{{ route('projects.show', ['projectId' => $projectId]) }}"
                    class="btn p-0"
                    >Подробнее</a>
            </span>
            @can('project.update', $projectId)
                <span class="text-muted">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <a href="{{ route('projects.edit', ['projectId' => $projectId]) }}"
                        class="btn p-0"
                        >Редактировать</a>
                </span>
            @endcan
            @can('project.delete', $projectId)
                <span class="text-muted">
                    <i class="fa-solid fa-trash-can"></i>
                    <button
                        type="button"
                        class="btn p-0" 
                        data-bs-toggle="modal" 
                        data-bs-target="#project-delete-confirmation" 
                        data-bs-project-name="{{ $name }}"
                        data-bs-project-id="{{ $projectId }}"                    
                        >Удалить</button>
                </span>
            @endcan
        </p>
    </div>
</div>
