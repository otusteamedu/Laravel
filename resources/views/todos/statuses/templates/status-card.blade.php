@php
/**
 * @var int $projectId
 */
@endphp
<template x-for="status in Object.values(statuses)">
    @can('todostatuses.manage', $projectId)
        <div class="card mb-3 col-12 col-md-5 mx-2" :style="{'border-color': status.color}">
            <div class="card-header fw-bold" :style="{'background-color': status.color}" 
                x-text="status.name">
            </div>
            <div class="card-body">
                <p class="card-text d-flex flex-wrap justify-content-end gap-3">
                    <span class="text-muted">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <button
                        x-data @click="$store.todoStatuses.formShow({ 
                            name: status.name,
                            statusId: status.statusId, 
                            color: status.color,
                            sort: status.sort
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
                            x-data @click="$store.todoStatuses.confirmDelete({
                                name: status.name,
                                statusId: status.statusId, 
                            })"
                            :data-bs-status-name="status.name"
                            :data-bs-project-id="$store.todoStatuses.projectId"                    
                            :data-bs-status-id="status.statusId"                    
                        >Удалить</button>
                    </span>
                </p>
            </div>
        </div>
    @endcan
</template>