<div class="modal fade" id="project-invite-reject-confirmation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div x-data class="modal-content">
            <form method="POST" :action="$store.projects.leftForm.action">
                @csrf
                @method('delete')
                <div class="modal-header">
                    <h5 class="modal-title">Отказ от участия</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Вы уверены что хотите отказаться от участия в проеке "<span x-text="$store.projects.leftForm.projectName"></span>"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  @click="$store.projects.clearForm()">Отмена</button>
                    <button type="submit" class="btn btn-danger">Отказаться</button>
                </div>
            </form>
        </div>
    </div>
</div>

