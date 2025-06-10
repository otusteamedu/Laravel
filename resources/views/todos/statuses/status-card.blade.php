@php
/**
 * @var int    $statusId
 * @var string $name
 * @var string $color
 * @var int    $sort
 */
@endphp
<div class="card mb-3 col-12 col-md-5 mx-2" style="border-color: {{ $color }}">
    <div class="card-header fw-bold" style="background-color: {{ $color }}">
        {{ $name }}
    </div>
    <div class="card-body">
        <p class="card-text d-flex flex-wrap justify-content-end gap-3">
            <span class="text-muted">
                <i class="fa-solid fa-pen-to-square"></i>
                <button
                    x-data @click="$store.todoStatuses.formShow({ 
                        name: '{{ $name }}',
                        statusId: {{ $statusId }}, 
                        color: '{{ $color }}',
                        sort: {{ $sort }}
                    })"
                    type="button"
                    class="btn p-0"
                    >Редактировать</button>
            </span>
            <span class="text-muted">
                <i class="fa-solid fa-trash-can"></i>
                <button
                    class="btn p-0"
                    type="button" 
                    data-bs-toggle="modal" 
                    data-bs-target="#todostatus-delete-confirmation" 
                    data-bs-status-name="{{ $name }}"
                    data-bs-project-id="{{ $projectId }}"                    
                    data-bs-status-id="{{ $statusId }}"                    
                    >Удалить</button>
            </span>
        </p>
    </div>
</div>
