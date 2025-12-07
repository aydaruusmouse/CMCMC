<nav class="navbar navbar-expand-lg">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="bi bi-person-circle me-2" style="font-size: 1.5rem; color: var(--primary-color);"></i>
            <span class="navbar-brand mb-0" style="font-weight: 600; font-size: 1.1rem;">{{ Auth::user()->name }}</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary" style="font-size: 0.75rem; padding: 0.5rem 0.875rem;">
                <i class="bi bi-shield-check me-1"></i>{{ ucfirst(Auth::user()->role) }}
            </span>
        </div>
    </div>
</nav>
