@php
/**
 * @var int    $projectId
 * @var string $name
 * @var string $description
 * @var string $created
 */
@endphp
<div class="p-4 tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
    <div class="mb-4">
        <h4 class="mb-4">Информация</h4>
        <div class="card mb-3">
            <div class="card-header fw-bold">
                {{ $name }}
            </div>
            <div class="card-body">
                <p class="card-text">{{ $description }}</p>
                <p class="card-text"><small class="text-muted">Создан: {{ $created }}</small></p>
                {{-- ?TODO сводные данные по задачам --}}
                <p class="card-text d-flex flex-wrap justify-content-end gap-3">
                    <span class="text-muted">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <a href="{{ route('projects.edit', ['projectId' => $projectId]) }}"
                            class="btn p-0"
                            >Редактировать</a>
                    </span>
                </p>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-header fw-bold">
                Участники проекта
            </div>
            <div class="card-body">
                В разработке
            </div>
        </div>
    </div>
</div>

@include('projects.partials.delete-confirmation')
