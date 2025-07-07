@php
/**
 * @var int $projectId
 */
@endphp
<div x-data class="modal fade" id="todostatus-delete-confirmation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="todostatus-delete-confirmation-form" method="POST" action="{{ route('project.todostatuses.destroy', ['projectId' => $projectId]) }}">
                @csrf
                <input name="status_id" type="hidden"
                    x-model="$store.todoStatuses.statusId">
                <div class="modal-header">
                    <h5 class="modal-title">Удаление статуса для задач</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы уверены что хотите удалить статус "<span class="status-name" x-text="$store.todoStatuses.name">></span>"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>
