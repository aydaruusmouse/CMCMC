@extends('layouts.app')

@section('title', 'Patients')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-people me-3"></i>My Patients</h1>
    <p class="text-muted">View all patients assigned to you</p>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>All Patients</h5>
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
                        <th>Active Package</th>
                        <th>Last Health Data</th>
                        <th>Total Consultations</th>
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
                                        <small class="text-muted">{{ $patient->phone ?? $patient->user->phone ?? 'N/A' }}</small>
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
                                @php
                                    $activeBooking = $patient->bookings->where('status', 'active')->first();
                                @endphp
                                @if($activeBooking)
                                    <span class="badge bg-success">{{ $activeBooking->package->name }}</span>
                                @else
                                    <span class="badge bg-secondary">No Active Package</span>
                                @endif
                            </td>
                            <td>
                                @if($patient->healthData->count() > 0)
                                    {{ $patient->healthData->first()->recorded_date->format('M d, Y') }}
                                    @if($patient->healthData->first()->blood_sugar)
                                        <br><small class="text-muted">BS: {{ $patient->healthData->first()->blood_sugar }} mg/dL</small>
                                    @endif
                                @else
                                    <span class="text-muted">No data</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $patient->consultations->count() }}</span>
                            </td>
                            <td>
                                @if($activeBooking)
                                    <a href="{{ route('doctor.consultations.create', $activeBooking) }}" 
                                       class="btn btn-sm btn-primary" title="Add Consultation">
                                        <i class="bi bi-clipboard-check"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox empty-icon"></i>
                                <p class="text-muted mt-3">No patients assigned yet</p>
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


