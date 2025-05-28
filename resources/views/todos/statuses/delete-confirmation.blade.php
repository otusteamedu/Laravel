<div class="modal fade" id="todostatus-delete-confirmation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="todostatus-delete-confirmation-form" method="POST" action="">
                @csrf
                @method('delete')
                <input type="hidden" name="statusId" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Удаление статуса для задач</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы уверены что хотите удалить статус "<span class="status-name"></span>"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts-bottom')
<script>
    var confirmModal = document.getElementById('todostatus-delete-confirmation')
        confirmModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            var statusName = button.getAttribute('data-bs-status-name')
            var projectId = button.getAttribute('data-bs-project-id')
            var statusId = button.getAttribute('data-bs-status-id')

            var nameWrapper = confirmModal.querySelector('.status-name')
            var inputValue = confirmModal.querySelector('.modal-body input')
            
            nameWrapper.textContent = statusName
            nameWrapper.inputValue = statusId

            form = document.getElementById('todostatus-delete-confirmation-form')

            form.action = '/todostatuses/' + projectId + '/' + statusId + '#statuses-tab'
        });
</script>
@endpush

