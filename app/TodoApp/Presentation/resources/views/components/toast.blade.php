@props([
    'success' => $success,
    'message' => $message,
])

<div class="toast-container position-absolute top-0 end-0 p-3">
    <div x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 2000)"
        class="toast align-items-center text-bg-{{ $success ? 'success' : 'danger' }} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                {{ $message }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Закрыть"></button>
        </div>
    </div>
</div>
