@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-speedometer2 me-3"></i>Admin Dashboard</h1>
    <p class="text-muted">Comprehensive overview of system statistics and activities</p>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card stat-card-primary">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="stat-label">Total Patients</div>
                    <div class="stat-value">{{ $stats['total_patients'] }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-arrow-up text-success"></i>
                <span class="text-muted small">All time</span>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card stat-card-info">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="stat-label">Total Bookings</div>
                    <div class="stat-value">{{ $stats['total_bookings'] }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-arrow-up text-success"></i>
                <span class="text-muted small">All time</span>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card stat-card-success">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="stat-label">Active Bookings</div>
                    <div class="stat-value">{{ $stats['active_bookings'] }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-activity text-success"></i>
                <span class="text-muted small">Currently active</span>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card stat-card-warning">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value">${{ number_format($stats['total_revenue'], 0) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-graph-up text-success"></i>
                <span class="text-muted small">All payments</span>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card stat-card-secondary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Pending Bookings</div>
                    <div class="stat-value">{{ $stats['pending_bookings'] ?? 0 }}</div>
                </div>
                <div class="stat-icon-small">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="stat-card stat-card-purple">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Total Doctors</div>
                    <div class="stat-value">{{ $stats['total_doctors'] ?? 0 }}</div>
                </div>
                <div class="stat-icon-small">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="stat-card stat-card-teal">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Total Agents</div>
                    <div class="stat-value">{{ $stats['total_agents'] ?? 0 }}</div>
                </div>
                <div class="stat-icon-small">
                    <i class="bi bi-person-workspace"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings Card -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Recent Bookings</h5>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Package</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ substr($booking->patient->user->name, 0, 1) }}
                                    </div>
                                    <span>{{ $booking->patient->user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $booking->doctor->user->name }}</td>
                            <td><span class="badge bg-info">{{ $booking->package->name }}</span></td>
                            <td>{{ $booking->booking_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $booking->status === 'active' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox empty-icon"></i>
                                <p class="text-muted mt-3">No bookings found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
