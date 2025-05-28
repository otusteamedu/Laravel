<div class="modal fade" id="project-delete-confirmation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="project-delete-confirmation-form" method="POST" action="">
                @csrf
                @method('delete')
                <input type="hidden" name="projectId" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Удаление проекта</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы уверены что хотите удалить проект "<span class="project-name"></span>"?
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
    var confirmModal = document.getElementById('project-delete-confirmation')
        confirmModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            var projectName = button.getAttribute('data-bs-project-name')
            var projectId = button.getAttribute('data-bs-project-id')

            var nameWrapper = confirmModal.querySelector('.project-name')
            var inputValue = confirmModal.querySelector('.modal-body input')
            
            nameWrapper.textContent = projectName
            nameWrapper.inputValue = projectId

            form = document.getElementById('project-delete-confirmation-form')

            form.action = '/projects/' + projectId
        });
</script>
@endpush

