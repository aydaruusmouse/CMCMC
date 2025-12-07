@php
    $user = Auth::user();
    $role = $user->role;
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

<aside class="sidebar text-white" style="width: 250px; min-height: 100vh;">
    <div class="p-4">
        <h4 class="mb-4 fw-bold text-white">
            <i class="bi bi-heart-pulse-fill me-2"></i>CMCMS
        </h4>
        <nav class="nav flex-column">
            @if($role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $currentRoute === 'admin.dashboard' ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ str_starts_with($currentRoute ?? '', 'admin.users') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Users
                </a>
                <a href="{{ route('admin.packages.index') }}" class="nav-link {{ str_starts_with($currentRoute ?? '', 'admin.packages') ? 'active' : '' }}">
                    <i class="bi bi-box-seam me-2"></i> Packages
                </a>
                <a href="{{ route('admin.discounts.index') }}" class="nav-link {{ str_starts_with($currentRoute ?? '', 'admin.discounts') ? 'active' : '' }}">
                    <i class="bi bi-percent me-2"></i> Discounts
                </a>
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ $currentRoute === 'admin.reports.index' ? 'active' : '' }}">
                    <i class="bi bi-graph-up me-2"></i> Reports
                </a>
            @elseif($role === 'agent')
                <a href="{{ route('agent.dashboard') }}" class="nav-link {{ str_starts_with($currentRoute, 'agent.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('agent.bookings.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'agent.bookings') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check me-2"></i> Bookings
                </a>
                <a href="{{ route('agent.patients.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'agent.patients') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Patients
                </a>
            @elseif($role === 'doctor')
                <a href="{{ route('doctor.dashboard') }}" class="nav-link {{ str_starts_with($currentRoute, 'doctor.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('doctor.patients.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'doctor.patients') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i> Patients
                </a>
                <a href="{{ route('doctor.consultations.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'doctor.consultations') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-data me-2"></i> Consultations
                </a>
            @elseif($role === 'patient')
                <a href="{{ route('patient.dashboard') }}" class="nav-link {{ str_starts_with($currentRoute, 'patient.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="{{ route('patient.health-data.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'patient.health-data') ? 'active' : '' }}">
                    <i class="bi bi-heart-pulse me-2"></i> Health Data
                </a>
                <a href="{{ route('patient.appointments.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'patient.appointments') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event me-2"></i> Appointments
                </a>
            @endif
            
            <hr class="text-white-50 my-3">
            
            <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                @csrf
                <button type="submit" class="nav-link text-start w-100 border-0 bg-transparent text-white">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </nav>
    </div>
</aside>
