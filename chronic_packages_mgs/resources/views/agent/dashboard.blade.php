@extends('layouts.app')

@section('title', 'Agent Dashboard')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-speedometer2 me-3"></i>Agent Dashboard</h1>
    <p class="text-muted">Manage patient bookings and registrations efficiently</p>
</div>

<!-- Statistics Cards -->
<div class="row g-2 mb-3">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-card-primary">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="stat-label">Total Bookings</div>
                    <div class="stat-value">{{ $stats['total_bookings'] }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-graph-up text-success"></i>
                <span class="text-muted small">All time</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-card-success">
            <div class="d-flex justify-content-between align-items-start mb-3">
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
    
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-card-warning">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="stat-label">Pending Bookings</div>
                    <div class="stat-value">{{ $stats['pending_bookings'] }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-hourglass text-warning"></i>
                <span class="text-muted small">Awaiting payment</span>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-card-info">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value">${{ number_format($stats['total_revenue'], 0) }}</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
            <div class="stat-trend">
                <i class="bi bi-graph-up-arrow text-success"></i>
                <span class="text-muted small">From bookings</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Recent Bookings</h5>
        <a href="{{ route('agent.bookings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>New Booking
        </a>
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
                        <th>Actions</th>
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
                            <td>
                                @if($booking->status === 'pending' && !$booking->payments->where('status', 'completed')->count())
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $booking->id }}">
                                        <i class="bi bi-check-circle me-1"></i>Confirm
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="text-muted mt-3">No bookings found</p>
                                <a href="{{ route('agent.bookings.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle me-2"></i>Create First Booking
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
