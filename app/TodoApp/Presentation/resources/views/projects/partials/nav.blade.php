@php
/**
 * @var string $active
 * @var int $projectId
 */
@endphp
<div class="p-4">
    <div class="nav navbar-nav nav-left flex-column">        
        <a href="{{ route('projects.show', ['projectId' => $projectId]) }}" 
            id="info-tab"
            @class([
                'nav-link',
                'active' => $active === 'project',
            ])
        >
            <i class="fa-solid fa-circle-info me-2"></i>Информация
        </a>
        <a href="{{ route('project.todos.index', ['projectId' => $projectId]) }}" 
            id="todo-tab"
            @class([
                'nav-link',
                'active' => $active === 'todos',
            ])
        >
            <i class="fa-solid fa-list-check me-2"></i>Задачи
        </a>
        @can('project.user.list', $projectId)
            <a href="{{ route('project.users.index', ['projectId' => $projectId]) }}" 
                id="user-tab"
                @class([
                    'nav-link',
                    'active' => $active === 'users',
                ])
            >
                <i class="fa-solid fa-users me-2"></i>Участники
            </a>
        @endcan
        @can('todostatuses.manage', $projectId)
            <div class="col"><hr></div>
            <div class="mb-2">
                <i class="fa-solid fa-gear me-2"></i>
                <span class="fw-bold">Управление</span>
            </div>
            <a href="{{ route('project.todostatuses.index', ['projectId' => $projectId]) }}"
                id="statuses-tab"
                @class([
                    'nav-link',
                    'active' => $active === 'statuses'
                ])
            >
                <i class="fa-solid fa-chart-simple me-2"></i>Статусы для задач
            </a>
        @endcan
    </div>
</div>
