@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="page-header">
    <h1>System Reports</h1>
    <p class="text-muted">View comprehensive system statistics and analytics</p>
</div>

<!-- Date Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="date_from" class="form-label">From Date</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-4">
                <label for="date_to" class="form-label">To Date</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $dateTo }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Overall Statistics -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total_patients'] }}</div>
            <div class="stat-label">Total Patients</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total_doctors'] }}</div>
            <div class="stat-label">Total Doctors</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total_agents'] }}</div>
            <div class="stat-label">Total Agents</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total_bookings'] }}</div>
            <div class="stat-label">Total Bookings</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['active_bookings'] }}</div>
            <div class="stat-label">Active Bookings</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-value">${{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-value">${{ number_format($stats['monthly_revenue'], 2) }}</div>
            <div class="stat-label">Period Revenue</div>
        </div>
    </div>
</div>

<!-- Package Sales -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Package Sales</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Package</th>
                        <th>Price</th>
                        <th>Bookings Count</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packageSales as $package)
                        <tr>
                            <td>{{ $package->name }}</td>
                            <td>${{ number_format($package->price, 2) }}</td>
                            <td>{{ $package->bookings_count }}</td>
                            <td>${{ number_format($package->bookings_count * $package->price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No package sales data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Doctor Performance -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Top Doctors by Bookings</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Specialty</th>
                        <th>Bookings Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctorPerformance as $doctor)
                        <tr>
                            <td>{{ $doctor->user->name }}</td>
                            <td>{{ $doctor->specialty }}</td>
                            <td>{{ $doctor->bookings_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No doctor performance data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Agent Performance -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Top Agents by Bookings</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Employee ID</th>
                        <th>Bookings Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agentPerformance as $agent)
                        <tr>
                            <td>{{ $agent->user->name }}</td>
                            <td>{{ $agent->employee_id ?? '-' }}</td>
                            <td>{{ $agent->bookings_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No agent performance data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Recent Payments -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Recent Payments</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Package</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $payment)
                        <tr>
                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                            <td>{{ $payment->patient->user->name }}</td>
                            <td>{{ $payment->booking->package->name }}</td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>{{ $payment->receipt_number ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No payment data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

