@extends('layouts.app')

@section('title', 'My Appointments')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-calendar-event me-3"></i>My Appointments</h1>
    <p class="text-muted">View and manage your appointment schedule</p>
</div>

<!-- Upcoming Appointments -->
@if($upcomingAppointments->count() > 0)
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Upcoming Appointments</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Package</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upcomingAppointments as $appointment)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ substr($appointment->doctor->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $appointment->doctor->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $appointment->doctor->specialty ?? 'General Medicine' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $appointment->appointment_date->format('h:i A') }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                @if($appointment->booking && $appointment->booking->package)
                                    <span class="badge bg-info">{{ $appointment->booking->package->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success">{{ ucfirst($appointment->status) }}</span>
                            </td>
                            <td>
                                @if($appointment->notes)
                                    <small>{{ Str::limit($appointment->notes, 50) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- All Appointments -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>All Appointments</h5>
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-primary active" onclick="filterAppointments('all')">All</button>
            <button type="button" class="btn btn-outline-primary" onclick="filterAppointments('upcoming')">Upcoming</button>
            <button type="button" class="btn btn-outline-primary" onclick="filterAppointments('past')">Past</button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Doctor</th>
                        <th>Package</th>
                        <th>Status</th>
                        <th>Consultation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr class="appointment-row" 
                            data-status="{{ $appointment->appointment_date >= now() && $appointment->status === 'scheduled' ? 'upcoming' : 'past' }}">
                            <td>
                                <div>
                                    <strong>{{ $appointment->appointment_date->format('M d, Y') }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $appointment->appointment_date->format('h:i A') }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ substr($appointment->doctor->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $appointment->doctor->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $appointment->doctor->specialty ?? 'General Medicine' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($appointment->booking && $appointment->booking->package)
                                    <span class="badge bg-info">{{ $appointment->booking->package->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $isUpcoming = $appointment->appointment_date >= now() && $appointment->status === 'scheduled';
                                    $isPast = $appointment->appointment_date < now() || in_array($appointment->status, ['completed', 'cancelled']);
                                @endphp
                                @if($isUpcoming)
                                    <span class="badge bg-success">{{ ucfirst($appointment->status) }}</span>
                                @elseif($appointment->status === 'completed')
                                    <span class="badge bg-primary">{{ ucfirst($appointment->status) }}</span>
                                @elseif($appointment->status === 'cancelled')
                                    <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                @else
                                    <span class="badge bg-warning">{{ ucfirst($appointment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($appointment->consultations->count() > 0)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Completed
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-calendar-x" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="text-muted mt-3">No appointments found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($appointments->hasPages())
        <div class="card-footer bg-light p-3">
            {{ $appointments->links() }}
        </div>
    @endif
</div>

@if($upcomingAppointments->count() == 0 && $pastAppointments->count() == 0)
<div class="alert alert-info mt-4">
    <i class="bi bi-info-circle me-2"></i>
    <strong>No Appointments:</strong> You don't have any appointments scheduled yet. Appointments are typically scheduled by your agent when you register for a package.
</div>
@endif

<script>
    function filterAppointments(filter) {
        const rows = document.querySelectorAll('.appointment-row');
        const buttons = document.querySelectorAll('.btn-group button');
        
        // Update button states
        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        
        // Filter rows
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            if (filter === 'all') {
                row.style.display = '';
            } else if (filter === 'upcoming' && status === 'upcoming') {
                row.style.display = '';
            } else if (filter === 'past' && status === 'past') {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection

