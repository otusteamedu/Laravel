@php
/**
 * @var int $projectId
 */
@endphp
<div class="col-12 card"
    x-data
    x-show="$store.todoStatuses.showForm"
    x-cloak
    x-transition
    >
    <form method="POST" :action="$store.todoStatuses.action" autocomplete="off">
        @csrf
        <input name="project_id" type="hidden"
            x-model="$store.todoStatuses.projectId">
        <input name="status_id" type="hidden"
            x-model="$store.todoStatuses.statusId">

        <div class="card-header"
            x-text="$store.todoStatuses.title"
        ></div>
        <div class="card-body">
            @include('todo-app::todos.statuses.form-field')
        </div>
        <div class="card-footer text-end">
            <button 
                class="btn btn-outline-secondary"
                x-data @click="$store.todoStatuses.formHide()"
                >
                Отменить</button>
            <button class="btn btn-outline-primary">Сохранить</button>
        </div>
    </form>
</div>
