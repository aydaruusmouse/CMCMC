@extends('layouts.app')

@section('title', 'Patients')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-people me-3"></i>Patients</h1>
    <p class="text-muted">View all patients registered through your bookings</p>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>All Patients</h5>
        <a href="{{ route('agent.bookings.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-2"></i>Register New Patient
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Location</th>
                        <th>Age</th>
                        <th>Total Bookings</th>
                        <th>Last Booking</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2">
                                        {{ substr($patient->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $patient->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $patient->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="bi bi-telephone me-1"></i>{{ $patient->phone ?? $patient->user->phone ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="bi bi-geo-alt me-1"></i>{{ $patient->city ?? 'N/A' }}
                                    @if($patient->village)
                                        <br><small class="text-muted">{{ $patient->village }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($patient->age)
                                    {{ $patient->age }} years
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $patient->bookings->count() }}</span>
                            </td>
                            <td>
                                @if($patient->bookings->count() > 0)
                                    {{ $patient->bookings->first()->booking_date->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $patient->bookings->first()->package->name }}
                                    </small>
                                @else
                                    <span class="text-muted">No bookings</span>
                                @endif
                            </td>
                            <td>
                                @if($patient->bookings->count() > 0)
                                    @php
                                        $latestBooking = $patient->bookings->first();
                                        $hasActiveBooking = $latestBooking->status === 'active';
                                    @endphp
                                    <span class="badge bg-{{ $hasActiveBooking ? 'success' : 'secondary' }}">
                                        {{ $hasActiveBooking ? 'Active' : ucfirst($latestBooking->status) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">No Status</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('agent.bookings.index', ['patient_id' => $patient->id]) }}" 
                                   class="btn btn-sm btn-outline-primary" title="View Bookings">
                                    <i class="bi bi-calendar-check"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #d1d5db;"></i>
                                <p class="text-muted mt-3">No patients found</p>
                                <a href="{{ route('agent.bookings.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle me-2"></i>Register First Patient
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($patients->hasPages())
        <div class="card-footer">
            {{ $patients->links() }}
        </div>
    @endif
</div>
@endsection


