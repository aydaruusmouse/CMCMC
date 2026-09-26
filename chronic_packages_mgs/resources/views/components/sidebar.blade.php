@php
    $user = Auth::user();
    $role = $user->role;
    $currentRoute = request()->route() ? (request()->route()->getName() ?? '') : '';

    $menus = [
        'admin' => [
            ['admin.dashboard', 'admin.dashboard', 'bi-grid-1x2', 'Dashboard'],
            ['admin.users.index', 'admin.users', 'bi-people', 'Users'],
            ['admin.packages.index', 'admin.packages', 'bi-box-seam', 'Packages'],
            ['admin.discounts.index', 'admin.discounts', 'bi-percent', 'Discounts'],
            ['admin.reports.index', 'admin.reports', 'bi-bar-chart-line', 'Reports'],
        ],
        'agent' => [
            ['agent.dashboard', 'agent.dashboard', 'bi-grid-1x2', 'Dashboard'],
            ['agent.bookings.index', 'agent.bookings', 'bi-calendar-check', 'Bookings'],
            ['agent.patients.index', 'agent.patients', 'bi-people', 'Patients'],
        ],
        'doctor' => [
            ['doctor.dashboard', 'doctor.dashboard', 'bi-grid-1x2', 'Dashboard'],
            ['doctor.patients.index', 'doctor.patients', 'bi-people', 'Patients'],
            ['doctor.consultations.index', 'doctor.consultations', 'bi-clipboard2-pulse', 'Consultations'],
        ],
        'patient' => [
            ['patient.dashboard', 'patient.dashboard', 'bi-grid-1x2', 'Dashboard'],
            ['patient.health-data.index', 'patient.health-data', 'bi-heart-pulse', 'Health Data'],
            ['patient.appointments.index', 'patient.appointments', 'bi-calendar-event', 'Appointments'],
        ],
    ];
@endphp

<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="sidebar-logo"><i class="bi bi-heart-pulse-fill"></i></span>
        <div class="lh-sm">
            <div class="sidebar-brand-name">CMCMS</div>
            <div class="sidebar-brand-sub">Chronic &amp; Maternal Care</div>
        </div>
        <button type="button" class="sidebar-close d-lg-none" data-sidebar-close aria-label="Close navigation">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section">Menu</div>
        @foreach($menus[$role] ?? [] as [$routeName, $prefix, $icon, $label])
            <a href="{{ route($routeName) }}" class="nav-link {{ str_starts_with($currentRoute, $prefix) ? 'active' : '' }}">
                <i class="bi {{ $icon }}"></i>
                <span>{{ $label }}</span>
            </a>
        @endforeach
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign out</span>
            </button>
        </form>
    </div>
</aside>
