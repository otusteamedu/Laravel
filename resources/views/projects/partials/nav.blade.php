<div class="p-4">
    <div class="nav navbar-nav nav-left flex-column">        
        <a href="#"
            class="nav-link active" 
            id="info-tab"
            data-bs-toggle="tab" 
            data-bs-target="#info"
            aria-controls="info" 
            aria-selected="true"
            role="tab"
        >
            <i class="fa-solid fa-circle-info me-2"></i>Информация
        </a>
            <a href="#"
            class="nav-link" 
            id="statuses-tab"
            data-bs-toggle="tab" 
            data-bs-target="#statuses"
            aria-controls="statuses" 
            aria-selected="false"
            role="tab"
        >
            <i class="fa-solid fa-chart-simple me-2"></i>Статусы для задач
        </a>
    </a>
    </div>
</div>

@push('scripts-bottom')
<script>
    window.onload = function()
    {
        const hash = (window.location.hash);

        if (hash.length) {
            const tabElenent = document.querySelector(hash)
            if (tabElenent.role === 'tab') {
                new bootstrap.Tab(tabElenent).show()
            }
        }
    }
</script>
@endpush
