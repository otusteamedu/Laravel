<div class="modal fade" id="project-jeft-confirmation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div x-data class="modal-content">
            <form method="POST" :action="$store.projects.leftForm.action">
                @csrf
                @method('delete')
                <div class="modal-header">
                    <h5 class="modal-title">Покинуть проект</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы уверены что хотите покинуть проект "<span x-text="$store.projects.leftForm.projectName"></span>"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  @click="$store.projects.clearForm()">Отмена</button>
                    <button type="submit" class="btn btn-danger">Покинуть</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts-bottom')
<script>
    var confirmModal = document.getElementById('project-delete-confirmation')
    confirmModal.addEventListener('hide.bs.modal', function (event) {
        console.log('_____')
    });
</script>
@endpush

