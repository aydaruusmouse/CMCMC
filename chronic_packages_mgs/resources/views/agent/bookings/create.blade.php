@extends('layouts.app')

@section('title', 'Create Booking')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-calendar-plus me-3"></i>Create New Booking</h1>
    <p class="text-muted">Register a new patient and create a booking</p>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('agent.bookings.store') }}">
            @csrf

            <!-- Patient Information -->
            <h5 class="mb-3"><i class="bi bi-person me-2"></i>Patient Information</h5>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="patient_name" class="form-label">Patient Name *</label>
                    <input type="text" class="form-control @error('patient_name') is-invalid @enderror" 
                           id="patient_name" name="patient_name" value="{{ old('patient_name') }}" required>
                    @error('patient_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="patient_email" class="form-label">Patient Email *</label>
                    <input type="email" class="form-control @error('patient_email') is-invalid @enderror" 
                           id="patient_email" name="patient_email" value="{{ old('patient_email') }}" required>
                    @error('patient_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <label for="patient_phone" class="form-label">Patient Phone *</label>
                    <input type="text" class="form-control @error('patient_phone') is-invalid @enderror" 
                           id="patient_phone" name="patient_phone" value="{{ old('patient_phone') }}" required>
                    @error('patient_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="patient_city" class="form-label">City *</label>
                    <input type="text" class="form-control @error('patient_city') is-invalid @enderror" 
                           id="patient_city" name="patient_city" value="{{ old('patient_city') }}" required>
                    @error('patient_city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="patient_village" class="form-label">Village *</label>
                    <input type="text" class="form-control @error('patient_village') is-invalid @enderror" 
                           id="patient_village" name="patient_village" value="{{ old('patient_village') }}" required>
                    @error('patient_village')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-2 mb-4">
                <div class="col-md-6">
                    <label for="patient_age" class="form-label">Age *</label>
                    <input type="number" class="form-control @error('patient_age') is-invalid @enderror" 
                           id="patient_age" name="patient_age" value="{{ old('patient_age') }}" min="1" required>
                    @error('patient_age')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Booking Information -->
            <hr class="my-4">
            <h5 class="mb-3"><i class="bi bi-calendar-check me-2"></i>Booking Information</h5>
            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <label for="doctor_id" class="form-label">Doctor *</label>
                    <select class="form-select @error('doctor_id') is-invalid @enderror" 
                            id="doctor_id" name="doctor_id" required>
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
                    <label for="package_id" class="form-label">Package *</label>
                    <select class="form-select @error('package_id') is-invalid @enderror" 
                            id="package_id" name="package_id" required>
                        <option value="">Select Package</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->price }}" 
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
                    <label for="booking_date" class="form-label">Booking Date *</label>
                    <input type="date" class="form-control @error('booking_date') is-invalid @enderror" 
                           id="booking_date" name="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" required>
                    @error('booking_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-4">
                    <label for="booking_type" class="form-label">Booking Type *</label>
                    <select class="form-select @error('booking_type') is-invalid @enderror" 
                            id="booking_type" name="booking_type" required>
                        <option value="">Select Type</option>
                        <option value="online" {{ old('booking_type') == 'online' ? 'selected' : '' }}>Online</option>
                        <option value="in-person" {{ old('booking_type') == 'in-person' ? 'selected' : '' }}>In-Person</option>
                    </select>
                    @error('booking_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="payment_method" class="form-label">Payment Method *</label>
                    <select class="form-select @error('payment_method') is-invalid @enderror" 
                            id="payment_method" name="payment_method" required>
                        <option value="">Select Method</option>
                        <option value="zaad" {{ old('payment_method') == 'zaad' ? 'selected' : '' }}>Zaad</option>
                        <option value="edahab" {{ old('payment_method') == 'edahab' ? 'selected' : '' }}>Edahab</option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="discount_id" class="form-label">Discount (Optional)</label>
                    <select class="form-select @error('discount_id') is-invalid @enderror" 
                            id="discount_id" name="discount_id">
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

            <!-- Additional Information -->
            <hr class="my-4">
            <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Additional Information</h5>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label for="source_of_booking" class="form-label">Source of Booking</label>
                    <input type="text" class="form-control @error('source_of_booking') is-invalid @enderror" 
                           id="source_of_booking" name="source_of_booking" value="{{ old('source_of_booking') }}">
                    @error('source_of_booking')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="where_heard_from" class="form-label">Where Did They Hear From Us?</label>
                    <input type="text" class="form-control @error('where_heard_from') is-invalid @enderror" 
                           id="where_heard_from" name="where_heard_from" value="{{ old('where_heard_from') }}">
                    @error('where_heard_from')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_new_patient" 
                               name="is_new_patient" value="1" {{ old('is_new_patient', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_new_patient">
                            New Patient
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="satisfaction_level" class="form-label">Satisfaction Level (1-5)</label>
                    <input type="number" class="form-control @error('satisfaction_level') is-invalid @enderror" 
                           id="satisfaction_level" name="satisfaction_level" 
                           value="{{ old('satisfaction_level') }}" min="1" max="5">
                    @error('satisfaction_level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <hr class="my-4">
            <div class="d-flex justify-content-between">
                <a href="{{ route('agent.bookings.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Create Booking
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

