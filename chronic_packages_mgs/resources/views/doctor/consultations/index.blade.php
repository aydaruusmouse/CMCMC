@extends('layouts.app')

@section('title', 'Consultations')

@section('content')
<div class="page-header">
    <h1>Consultations</h1>
    <p class="text-muted">View all consultation records</p>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Package</th>
                        <th>Prescription</th>
                        <th>Next Visit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $consultation)
                        <tr>
                            <td><strong>{{ $consultation->created_at->format('M d, Y') }}</strong></td>
                            <td>{{ $consultation->patient->user->name }}</td>
                            <td><span class="badge bg-info">{{ $consultation->booking->package->name }}</span></td>
                            <td>
                                @if($consultation->prescription)
                                    <small>{{ Str::limit($consultation->prescription, 50) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($consultation->next_visit_date)
                                    <strong>{{ $consultation->next_visit_date->format('M d, Y') }}</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('doctor.consultations.create', $consultation->booking) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil me-1"></i>View/Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No consultations found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light p-3">
            {{ $consultations->links() }}
        </div>
    </div>
</div>
@endsection
