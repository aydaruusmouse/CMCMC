@extends('layouts.app')

@section('title', 'Doctor Dashboard')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-speedometer2 me-3"></i>Doctor Dashboard</h1>
    <p class="text-muted">Monitor patient health data and manage consultations</p>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="stat-card stat-card-primary">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="stat-label">Active Patients</div>
                    <div class="stat-value">{{ $stats['active_patients'] }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-heart-pulse text-danger"></i>
                <span class="text-muted small">Under care</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="stat-card stat-card-info">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="stat-label">Upcoming Appointments</div>
                    <div class="stat-value">{{ $stats['upcoming_appointments'] }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-clock text-info"></i>
                <span class="text-muted small">Scheduled</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="stat-card stat-card-warning">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="stat-label">Pending Consultations</div>
                    <div class="stat-value">{{ $stats['pending_consultations'] }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-clipboard-data"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-exclamation-circle text-warning"></i>
                <span class="text-muted small">Requires attention</span>
            </div>
        </div>
    </div>
</div>

<!-- Critical Alerts -->
@if($criticalAlerts && $criticalAlerts->count() > 0)
<div class="card mb-4 border-danger shadow-lg">
    <div class="card-header bg-danger text-white d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.5rem;"></i>
        <h5 class="mb-0">Critical Health Alerts</h5>
        <span class="badge bg-light text-danger ms-auto">{{ $criticalAlerts->count() }}</span>
    </div>
    <div class="card-body">
        <div class="list-group list-group-flush">
            @foreach($criticalAlerts as $alert)
                <div class="list-group-item border-0 px-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar-circle-small me-2 bg-danger text-white">
                                    {{ substr($alert->patient->user->name, 0, 1) }}
                                </div>
                                <strong class="me-2">{{ $alert->patient->user->name }}</strong>
                                <span class="badge bg-danger">Critical</span>
                            </div>
                            <div class="ms-4">
                                @if($alert->blood_sugar)
                                    <span class="badge bg-warning me-2">
                                        <i class="bi bi-droplet me-1"></i>Blood Sugar: {{ $alert->blood_sugar }} mg/dL
                                    </span>
                                @endif
                                @if($alert->systolic_bp)
                                    <span class="badge bg-danger me-2">
                                        <i class="bi bi-heart-pulse me-1"></i>BP: {{ $alert->systolic_bp }}/{{ $alert->diastolic_bp }} mmHg
                                    </span>
                                @endif
                            </div>
                        </div>
                        <small class="text-muted">{{ $alert->recorded_date->format('M d, Y') }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Upcoming Appointments -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Upcoming Appointments</h5>
        <a href="{{ route('doctor.consultations.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingAppointments as $appointment)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ substr($appointment->patient->user->name, 0, 1) }}
                                    </div>
                                    <span>{{ $appointment->patient->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong><br>
                                <small class="text-muted">{{ $appointment->appointment_date->format('h:i A') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($appointment->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('doctor.consultations.create', $appointment->booking) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-clipboard-check me-1"></i>Start Consultation
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-calendar-x" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="text-muted mt-3">No upcoming appointments</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
