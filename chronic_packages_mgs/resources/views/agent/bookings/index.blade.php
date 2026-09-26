@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
@if(session('patient_credentials'))
    <!-- Patient Credentials Modal -->
    <div class="modal fade" id="credentialsModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-check-circle me-2"></i>Patient Account Created Successfully!
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Important:</strong> Please share these login credentials with the patient. They will need these to access their patient portal.
                    </div>
                    
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="bi bi-person-circle me-2"></i>Patient Login Credentials</h6>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Patient Name:</label>
                                <div class="fw-bold">{{ session('patient_credentials')['name'] }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Phone Number:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control fw-bold" id="patientPhone" 
                                           value="{{ session('patient_credentials')['phone'] }}" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('patientPhone')">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-0">
                                <label class="form-label text-muted small">Password:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control fw-bold" id="patientPassword" 
                                           value="{{ session('patient_credentials')['password'] }}" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('patientPassword')">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-shield-check me-1"></i>
                            Patient can log in using Phone Number and Password at: <a href="{{ route('login') }}" target="_blank">{{ route('login') }}</a>
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <i class="bi bi-check-circle me-2"></i>I've Saved These Credentials
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1>Bookings</h1>
        <p class="text-muted">Manage all patient bookings</p>
    </div>
    <a href="{{ route('agent.bookings.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>New Booking
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Package</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>{{ $booking->patient->user->name }}</td>
                            <td>{{ $booking->doctor->user->name }}</td>
                            <td>{{ $booking->package->name }}</td>
                            <td>{{ $booking->booking_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $booking->status === 'active' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td>
                                @if($booking->payments->where('status', 'completed')->count() > 0)
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status === 'pending' && $booking->payments->where('status', 'completed')->count() == 0)
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $booking->id }}">
                                        <i class="bi bi-check-circle me-1"></i>Confirm Payment
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No bookings found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="card-footer bg-light p-3">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Payment Modals - Outside table structure -->
@foreach($bookings as $booking)
    @if($booking->status === 'pending' && $booking->payments->where('status', 'completed')->count() == 0)
        <div class="modal fade" id="paymentModal{{ $booking->id }}" tabindex="-1" aria-labelledby="paymentModalLabel{{ $booking->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel{{ $booking->id }}">
                            <i class="bi bi-credit-card me-2"></i>Confirm Payment
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('agent.bookings.confirm-payment', $booking) }}" method="POST" id="paymentForm{{ $booking->id }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <p class="mb-1"><strong>Patient:</strong> {{ $booking->patient->user->name }}</p>
                                <p class="mb-1"><strong>Amount:</strong> ${{ number_format($booking->final_price, 2) }}</p>
                                <p class="mb-3"><strong>Method:</strong> {{ ucfirst($booking->payment_method) }}</p>
                            </div>
                            <div class="mb-3">
                                <label for="transaction_id{{ $booking->id }}" class="form-label">Transaction ID *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="transaction_id{{ $booking->id }}" 
                                       name="transaction_id" 
                                       required 
                                       autocomplete="off"
                                       placeholder="Enter transaction ID">
                                <small class="text-muted">Enter the transaction ID from {{ ucfirst($booking->payment_method) }}</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>Confirm Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@if(session('patient_credentials'))
<script>
    // Auto-show modal when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('credentialsModal'));
        modal.show();
    });

    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        element.setSelectionRange(0, 99999); // For mobile devices
        navigator.clipboard.writeText(element.value).then(function() {
            // Show feedback
            const btn = element.nextElementSibling;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check"></i>';
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
            setTimeout(function() {
                btn.innerHTML = originalHTML;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }, 2000);
        });
    }
</script>
@endif
@endsection

