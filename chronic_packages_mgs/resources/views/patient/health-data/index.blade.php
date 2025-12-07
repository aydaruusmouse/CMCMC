@extends('layouts.app')

@section('title', 'Health Data')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>My Health Data</h1>
        <p class="text-muted">View and manage your health records</p>
    </div>
    <a href="{{ route('patient.health-data.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Record New Data
    </a>
</div>

@if($activeBooking)
<div class="alert alert-info mb-4">
    <strong><i class="bi bi-info-circle me-2"></i>Active Package:</strong> {{ $activeBooking->package->name }}
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Blood Sugar</th>
                        <th>Blood Pressure</th>
                        <th>Weight</th>
                        <th>Temperature</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($healthData as $data)
                        <tr>
                            <td><strong>{{ $data->recorded_date->format('M d, Y') }}</strong></td>
                            <td>
                                @if($data->blood_sugar)
                                    <span class="badge bg-info">{{ $data->blood_sugar }} mg/dL</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($data->systolic_bp)
                                    <span class="badge bg-primary">{{ $data->systolic_bp }}/{{ $data->diastolic_bp }} mmHg</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($data->weight)
                                    <strong>{{ $data->weight }} kg</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($data->temperature)
                                    {{ $data->temperature }}°C
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($data->notes)
                                    <small>{{ Str::limit($data->notes, 50) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <p class="text-muted mb-3">No health data recorded yet.</p>
                                <a href="{{ route('patient.health-data.create') }}" class="btn btn-primary">Record Your First Data</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($healthData->hasPages())
            <div class="card-footer bg-light p-3">
                {{ $healthData->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
