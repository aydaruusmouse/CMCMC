@php
    $navUser = Auth::user();
    $initials = collect(explode(' ', trim($navUser->name)))->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');
@endphp

<header class="topbar">
    <div class="d-flex align-items-center gap-3">
        <button type="button" class="topbar-toggle d-lg-none" data-sidebar-toggle aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-title">
            <span class="topbar-eyebrow">{{ ucfirst($navUser->role) }} portal</span>
            <span class="topbar-page">{{ now()->format('l, j F Y') }}</span>
        </div>
    </div>

    <div class="dropdown">
        <button class="topbar-user" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="avatar-circle avatar-sm">{{ $initials }}</span>
            <span class="d-none d-sm-flex flex-column text-start lh-sm">
                <span class="topbar-user-name">{{ $navUser->name }}</span>
                <span class="topbar-user-role">{{ ucfirst($navUser->role) }}</span>
            </span>
            <i class="bi bi-chevron-down small text-muted d-none d-sm-inline"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li class="px-3 py-2">
                <div class="fw-semibold">{{ $navUser->name }}</div>
                <div class="small text-muted">{{ $navUser->phone ?? $navUser->email }}</div>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>Sign out
                    </button>
                </form>
            </li>
        </ul>
    </div>
</header>
