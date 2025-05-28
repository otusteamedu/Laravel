@php
/**
 * @var int $projectId
 * @var \App\Services\UseCases\Queries\Project\FetchWithRelations\TodoStatusDTO[] $statuses
*/
@endphp
<div class="p-4 tab-pane fade" id="statuses" role="tabpanel" aria-labelledby="statuses-tab">
    <div class="mb-4">
        <h4 class="mb-4">Статусы для задач</h4>
        <div class="col-12 my-3 text-end">
            <button
                type="button" 
                class="btn btn-outline-primary"
                x-data @click="$store.todoStatuses.formShow()"
                >
                Добавить статус
            </button>
        </div>
        <div class="d-flex flex-wrap">
            @foreach($statuses as $status)
            @include('todos.statuses.status-card', [
                'projectId' => $projectId,
                'statusId'  => $status->id,
                'name'      => $status->name,
                'color'     => $status->color,
                'sort'      => $status->sort,
            ])
            @endforeach
        </div>
    </div>
    <div class="mb-4">
        @include('todos.statuses.form', [
            'projectId' => $projectId,
        ])
    </div>
</div>
@include('todos.statuses.delete-confirmation')

<script>
    document.addEventListener('alpine:init', () => {

        Alpine.store('todoStatuses', {
            showForm: {{  empty(json_decode($errors)) ? 'false' : 'true' }},
            title: 'Добавить статус',
            projectId: {{ $projectId }},
            statusId:  "{{ old('id', null) }}",
            name: "{{ old('name', '') }}",
            sort: {{ old('sort', 100) }},
            color: "{{ old('color', '#f8f9fa') }}",
            action: "{{ route('todostatuses.store') }}#statuses-tab",
 
            init() {
                if (this.statusId !== null) {
                    this.title = 'Редактировать статус'
                } 
            },

            formShow(data = null) {
                this.showForm = true

                if (data !== null) {
                    this.name     = data.name
                    this.color    = data.color
                    this.sort     = data.sort
                    this.statusId = data.statusId
                    this.action   = "{{ route('todostatuses.update') }}#statuses-tab"
                }
            },

            formHide() {
                this.showForm = false

                this.formClear()
            },

            formClear() {
                this.showForm = false
                this.statusId = null
                this.name    = ''
                this.sort    = 100
                this.color   = 'f8f9fa'
                this.action  = "{{ route('todostatuses.store') }}#statuses-tab"
            },
        })
    })
</script>