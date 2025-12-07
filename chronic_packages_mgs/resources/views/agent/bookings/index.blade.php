@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
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

                        <!-- Payment Modal -->
                        <div class="modal fade" id="paymentModal{{ $booking->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><i class="bi bi-credit-card me-2"></i>Confirm Payment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('agent.bookings.confirm-payment', $booking) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <p><strong>Amount:</strong> ${{ number_format($booking->final_price, 2) }}</p>
                                            <p><strong>Method:</strong> {{ ucfirst($booking->payment_method) }}</p>
                                            <div class="mb-3">
                                                <label for="transaction_id" class="form-label">Transaction ID *</label>
                                                <input type="text" class="form-control" id="transaction_id" name="transaction_id" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Confirm Payment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No bookings found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light p-3">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
