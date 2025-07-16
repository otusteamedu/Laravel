@php
/**
 * @var int    $projectId
 * @var string $name
 * @var string $description
 * @var string $created
 */
@endphp
<div id="project-list-{{ $projectId }}" class="project-card card mb-3">
    <div class="card-header d-flex justify-content-between">
        <div><span class="fw-bold">{{ $name }}</span></div>
        @can('project.user.join', [$projectId, Auth::user()->id])
        @elsecan('project.user.left', [$projectId, Auth::user()->id])
            <span class="text-muted">
                <i class="ps-2 fa-solid fa-ban text-danger"></i>
                <span role="button"
                    id="project-left-btn-{{ $projectId }}"
                    data-bs-toggle="modal" 
                    data-bs-target="#project-left-confirmation" 
                    x-data @click="$store.projects.left('{{ route('project.users.left', [$projectId, Auth::user()->id])}}', '{{ $name }}')"
                    >Покинуть</span>
            </span>
        @endcan
    </div>
    <div class="card-body">
        <p class="card-text">{{ $description }}</p>
        <p class="card-text"><span class="text-muted">Создан: {{ $created }}</span></p>

        <p class="card-text d-flex flex-wrap justify-content-end gap-3">
            @can('project.view', $projectId)
                <span class="text-muted">
                    <i class="fa-solid fa-eye"></i>
                    <a href="{{ route('projects.show', ['projectId' => $projectId]) }}"
                        class="btn p-0"
                        >Подробнее</a>
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

    @can('project.user.join', [$projectId, Auth::user()->id])
        <div class="card-footer bg-info-subtle">
            <span>Вас пригласили к участию в проекте</span>
                <span class="text-muted">
                    <i class="ps-2 fa-solid fa-circle-check text-success"></i>
                    <span role="button"
                        id="invite-accept-btn-{{ $projectId }}"
                        class="fw-bold"
                        x-data @click="$store.projects.join('{{ route('project.users.join', [$projectId, Auth::user()->id])}}')"
                        >Принять</span>
                </span>
                <span class="text-muted">
                    <i class="ps-2 fa-solid fa-ban text-danger"></i>
                    <span role="button"
                        id="invite-reject-btn-{{ $projectId }}"
                        class="fw-bold"
                        data-bs-toggle="modal" 
                        data-bs-target="#project-invite-reject-confirmation" 
                        x-data @click="$store.projects.left('{{ route('project.users.left', [$projectId, Auth::user()->id])}}', '{{ $name }}')"
                        >Отказаться</span>
                </span>
            </span>
        </div>
    @endcan
</div>
