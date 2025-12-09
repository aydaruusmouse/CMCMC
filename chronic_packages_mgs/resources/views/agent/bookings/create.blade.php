@extends('layouts.app')

@section('title', 'Create Booking')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1><i class="bi bi-calendar-plus me-3"></i>Create New Booking</h1>
        <p class="text-muted mb-0">Register a new patient and create a booking</p>
    </div>
    <a href="{{ route('agent.bookings.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>

<form method="POST" action="{{ route('agent.bookings.store') }}">
    @csrf

    <!-- Patient Information Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Patient Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="patient_name" class="form-label fw-semibold">
                        <i class="bi bi-person me-1 text-primary"></i>Patient Name <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('patient_name') is-invalid @enderror" 
                           id="patient_name" 
                           name="patient_name" 
                           value="{{ old('patient_name') }}" 
                           placeholder="Enter patient full name"
                           required>
                    @error('patient_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="patient_phone" class="form-label fw-semibold">
                        <i class="bi bi-telephone me-1 text-primary"></i>Patient Phone <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('patient_phone') is-invalid @enderror" 
                           id="patient_phone" 
                           name="patient_phone" 
                           value="{{ old('patient_phone') }}" 
                           placeholder="e.g., 612345678"
                           required>
                    @error('patient_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="patient_city" class="form-label fw-semibold">
                        <i class="bi bi-geo-alt me-1 text-primary"></i>City <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('patient_city') is-invalid @enderror" 
                           id="patient_city" 
                           name="patient_city" 
                           value="{{ old('patient_city') }}" 
                           placeholder="Enter city name"
                           required>
                    @error('patient_city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="patient_village" class="form-label fw-semibold">
                        <i class="bi bi-geo me-1 text-primary"></i>Village <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control @error('patient_village') is-invalid @enderror" 
                           id="patient_village" 
                           name="patient_village" 
                           value="{{ old('patient_village') }}" 
                           placeholder="Enter village name"
                           required>
                    @error('patient_village')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-0">
                <div class="col-md-6">
                    <label for="patient_age" class="form-label fw-semibold">
                        <i class="bi bi-calendar3 me-1 text-primary"></i>Age <span class="text-danger">*</span>
                    </label>
                    <input type="number" 
                           class="form-control @error('patient_age') is-invalid @enderror" 
                           id="patient_age" 
                           name="patient_age" 
                           value="{{ old('patient_age') }}" 
                           min="1" 
                           max="120"
                           placeholder="Enter age"
                           required>
                    @error('patient_age')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Information Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Booking Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="doctor_id" class="form-label fw-semibold">
                        <i class="bi bi-person-badge me-1 text-primary"></i>Doctor <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('doctor_id') is-invalid @enderror" 
                            id="doctor_id" 
                            name="doctor_id" 
                            required>
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name }} - {{ $doctor->specialty }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="package_id" class="form-label fw-semibold">
                        <i class="bi bi-box-seam me-1 text-primary"></i>Package <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('package_id') is-invalid @enderror" 
                            id="package_id" 
                            name="package_id" 
                            required>
                        <option value="">Select Package</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" 
                                    data-price="{{ $package->price }}" 
                                    {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - ${{ number_format($package->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="booking_date" class="form-label fw-semibold">
                        <i class="bi bi-calendar-event me-1 text-primary"></i>Booking Date <span class="text-danger">*</span>
                    </label>
                    <input type="date" 
                           class="form-control @error('booking_date') is-invalid @enderror" 
                           id="booking_date" 
                           name="booking_date" 
                           value="{{ old('booking_date', date('Y-m-d')) }}" 
                           required>
                    @error('booking_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-0">
                <div class="col-md-4">
                    <label for="booking_type" class="form-label fw-semibold">
                        <i class="bi bi-laptop me-1 text-primary"></i>Booking Type <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('booking_type') is-invalid @enderror" 
                            id="booking_type" 
                            name="booking_type" 
                            required>
                        <option value="">Select Type</option>
                        <option value="online" {{ old('booking_type') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="in-person" {{ old('booking_type') == 'in-person' ? 'selected' : '' }}>In-Person</option>
                    </select>
                    @error('booking_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="payment_method" class="form-label fw-semibold">
                        <i class="bi bi-credit-card me-1 text-primary"></i>Payment Method <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('payment_method') is-invalid @enderror" 
                            id="payment_method" 
                            name="payment_method" 
                            required>
                        <option value="">Select Method</option>
                        <option value="zaad" {{ old('payment_method') == 'zaad' ? 'selected' : '' }}>Zaad</option>
                        <option value="edahab" {{ old('payment_method') == 'edahab' ? 'selected' : '' }}>Edahab</option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="discount_id" class="form-label fw-semibold">
                        <i class="bi bi-percent me-1 text-primary"></i>Discount (Optional)
                    </label>
                    <select class="form-select @error('discount_id') is-invalid @enderror" 
                            id="discount_id" 
                            name="discount_id">
                        <option value="">No Discount</option>
                        @foreach($discounts as $discount)
                            <option value="{{ $discount->id }}" {{ old('discount_id') == $discount->id ? 'selected' : '' }}>
                                {{ $discount->name }} - 
                                @if($discount->type === 'percentage')
                                    {{ $discount->value }}%
                                @else
                                    ${{ number_format($discount->value, 2) }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('discount_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Additional Information</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="source_of_booking" class="form-label fw-semibold">
                        <i class="bi bi-funnel me-1 text-primary"></i>Source of Booking
                    </label>
                    <input type="text" 
                           class="form-control @error('source_of_booking') is-invalid @enderror" 
                           id="source_of_booking" 
                           name="source_of_booking" 
                           value="{{ old('source_of_booking') }}"
                           placeholder="e.g., Referral, Walk-in, Online">
                    @error('source_of_booking')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="where_heard_from" class="form-label fw-semibold">
                        <i class="bi bi-megaphone me-1 text-primary"></i>Where Did They Hear From Us?
                    </label>
                    <input type="text" 
                           class="form-control @error('where_heard_from') is-invalid @enderror" 
                           id="where_heard_from" 
                           name="where_heard_from" 
                           value="{{ old('where_heard_from') }}"
                           placeholder="e.g., Social media, Friend, Advertisement">
                    @error('where_heard_from')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-3 mb-0">
                <div class="col-md-6">
                    <div class="form-check p-3 border rounded">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="is_new_patient" 
                               name="is_new_patient" 
                               value="1" 
                               {{ old('is_new_patient', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="is_new_patient">
                            <i class="bi bi-person-plus me-1 text-primary"></i>New Patient
                        </label>
                        <small class="d-block text-muted ms-4 mt-1">Check if this is the patient's first visit</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="satisfaction_level" class="form-label fw-semibold">
                        <i class="bi bi-star me-1 text-primary"></i>Satisfaction Level (1-5)
                    </label>
                    <input type="number" 
                           class="form-control @error('satisfaction_level') is-invalid @enderror" 
                           id="satisfaction_level" 
                           name="satisfaction_level" 
                           value="{{ old('satisfaction_level') }}" 
                           min="1" 
                           max="5"
                           placeholder="Rate from 1 to 5">
                    <small class="text-muted">1 = Poor, 5 = Excellent</small>
                    @error('satisfaction_level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('agent.bookings.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-check-circle me-2"></i>Create Booking
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

