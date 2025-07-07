<div class="p-4">
    <div class="nav navbar-nav nav-left flex-column">        
        <a href="#"
            class="nav-link active" 
            id="account-tab"
            data-bs-toggle="tab" 
            data-bs-target="#account"
            aria-controls="account" 
            aria-selected="true"
            role="tab"
        >
            <i class="fa-solid fa-user me-2"></i>Профиль</button>
        <a href="#"
            class="nav-link" 
            id="security-tab"
            data-bs-toggle="tab" 
            data-bs-target="#security"
            aria-controls="security" 
            aria-selected="false"
            role="tab"
        >
            <i class="fa-solid fa-lock me-2"></i>Безопасность
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
