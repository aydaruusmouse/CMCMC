@extends('layouts.app')

@section('title', 'Patient Dashboard')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-heart-pulse me-3"></i>Welcome, {{ Auth::user()->name }}</h1>
    <p class="text-muted">Your comprehensive health overview and recent activities</p>
</div>

@if($activeBooking)
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-primary shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Active Package</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="package-icon me-3">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $activeBooking->package->name }}</h4>
                        <p class="text-muted mb-0 small">Active since {{ $activeBooking->booking_date->format('M d, Y') }}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <small class="text-muted d-block">Doctor</small>
                        <strong>{{ $activeBooking->doctor->user->name }}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Specialty</small>
                        <strong>{{ $activeBooking->doctor->specialty }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($healthSummary['latest'])
    <div class="col-md-6">
        <div class="card border-success shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-activity me-2"></i>Latest Health Data</h5>
            </div>
            <div class="card-body">
                <div class="health-metrics">
                    @if($healthSummary['latest']->blood_sugar)
                        <div class="metric-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-droplet-fill text-danger me-2"></i>Blood Sugar</span>
                                <strong class="text-danger">{{ $healthSummary['latest']->blood_sugar }} mg/dL</strong>
                            </div>
                        </div>
                    @endif
                    @if($healthSummary['latest']->systolic_bp)
                        <div class="metric-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-heart-pulse-fill text-danger me-2"></i>Blood Pressure</span>
                                <strong class="text-danger">{{ $healthSummary['latest']->systolic_bp }}/{{ $healthSummary['latest']->diastolic_bp }} mmHg</strong>
                            </div>
                        </div>
                    @endif
                    @if($healthSummary['latest']->weight)
                        <div class="metric-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-speedometer2 text-primary me-2"></i>Weight</span>
                                <strong class="text-primary">{{ $healthSummary['latest']->weight }} kg</strong>
                            </div>
                        </div>
                    @endif
                </div>
                <hr>
                <small class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>Recorded: {{ $healthSummary['latest']->recorded_date->format('M d, Y') }}
                </small>
            </div>
        </div>
    </div>
    @endif
</div>
@endif

<!-- Health Statistics -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card stat-card-primary">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="stat-label">Health Records</div>
                    <div class="stat-value">{{ $healthSummary['total_records'] ?? 0 }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-file-medical-fill"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-graph-up text-success"></i>
                <span class="text-muted small">Total entries</span>
            </div>
        </div>
    </div>
    @if(isset($healthSummary['average_blood_sugar']) && $healthSummary['average_blood_sugar'])
    <div class="col-md-4">
        <div class="stat-card stat-card-danger">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="stat-label">Avg Blood Sugar</div>
                    <div class="stat-value">{{ number_format($healthSummary['average_blood_sugar'], 1) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-droplet-fill"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-activity text-info"></i>
                <span class="text-muted small">Last 30 days</span>
            </div>
        </div>
    </div>
    @endif
    @if(isset($healthSummary['average_weight']) && $healthSummary['average_weight'])
    <div class="col-md-4">
        <div class="stat-card stat-card-info">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="stat-label">Avg Weight</div>
                    <div class="stat-value">{{ number_format($healthSummary['average_weight'], 1) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-speedometer2"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-graph-up text-success"></i>
                <span class="text-muted small">kg (30 days avg)</span>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Appointments and Consultations -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Upcoming Appointments</h5>
                <a href="{{ route('patient.appointments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @forelse($upcomingAppointments as $appointment)
                    <div class="appointment-item mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-start">
                            <div class="appointment-icon me-3">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <strong class="d-block">{{ $appointment->doctor->user->name }}</strong>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>{{ $appointment->appointment_date->format('M d, Y h:i A') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x" style="font-size: 2.5rem; color: #d1d5db;"></i>
                        <p class="text-muted mt-2 mb-0">No upcoming appointments</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Recent Consultations</h5>
            </div>
            <div class="card-body">
                @forelse($recentConsultations as $consultation)
                    <div class="consultation-item mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-start">
                            <div class="consultation-icon me-3">
                                <i class="bi bi-file-medical"></i>
                            </div>
                            <div class="flex-grow-1">
                                <strong class="d-block">Dr. {{ $consultation->doctor->user->name }}</strong>
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $consultation->created_at->format('M d, Y') }}
                                </small>
                                @if($consultation->prescription)
                                    <p class="small mb-0 text-muted">{{ Str::limit($consultation->prescription, 80) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 2.5rem; color: #d1d5db;"></i>
                        <p class="text-muted mt-2 mb-0">No consultations yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
