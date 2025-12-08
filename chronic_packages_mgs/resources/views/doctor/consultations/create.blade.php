@extends('layouts.app')

@section('title', 'New Consultation')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="bi bi-clipboard-check me-3"></i>New Consultation</h1>
        <p class="text-muted">Record consultation notes and prescription for {{ $patient->user->name }}</p>
    </div>
    <a href="{{ route('doctor.consultations.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>

<div class="row g-3">
    <!-- Patient Information Card -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Patient Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Name:</strong><br>
                    <span>{{ $patient->user->name }}</span>
                </div>
                <div class="mb-3">
                    <strong>Email:</strong><br>
                    <span>{{ $patient->user->email }}</span>
                </div>
                <div class="mb-3">
                    <strong>Phone:</strong><br>
                    <span>{{ $patient->phone ?? $patient->user->phone ?? 'N/A' }}</span>
                </div>
                <div class="mb-3">
                    <strong>Location:</strong><br>
                    <span>{{ $patient->city ?? 'N/A' }}</span>
                    @if($patient->village)
                        <br><small class="text-muted">{{ $patient->village }}</small>
                    @endif
                </div>
                <div class="mb-3">
                    <strong>Age:</strong><br>
                    <span>{{ $patient->age ?? 'N/A' }} @if($patient->age) years @endif</span>
                </div>
                <div class="mb-0">
                    <strong>Package:</strong><br>
                    <span class="badge bg-info">{{ $booking->package->name }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Health Data Card -->
        @if($healthData->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-heart-pulse me-2"></i>Recent Health Data</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($healthData->take(5) as $data)
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $data->recorded_date->format('M d, Y') }}</strong>
                                    @if($data->blood_sugar)
                                        <br><small>BS: {{ $data->blood_sugar }} mg/dL</small>
                                    @endif
                                    @if($data->systolic_bp && $data->diastolic_bp)
                                        <br><small>BP: {{ $data->systolic_bp }}/{{ $data->diastolic_bp }} mmHg</small>
                                    @endif
                                    @if($data->weight)
                                        <br><small>Weight: {{ $data->weight }} kg</small>
                                    @endif
                                    @if($data->temperature)
                                        <br><small>Temp: {{ $data->temperature }}°C</small>
                                    @endif
                                    @if($data->bp_symptoms)
                                        <br><small class="text-muted">{{ Str::limit($data->bp_symptoms, 50) }}</small>
                                    @elseif($data->maternal_symptoms)
                                        <br><small class="text-muted">{{ Str::limit($data->maternal_symptoms, 50) }}</small>
                                    @elseif($data->pediatric_symptoms)
                                        <br><small class="text-muted">{{ Str::limit($data->pediatric_symptoms, 50) }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Consultation Form -->
    <div class="col-lg-8">
        <form action="{{ route('doctor.consultations.store', $booking) }}" method="POST">
            @csrf
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Consultation Form</h5>
                </div>
                <div class="card-body">
                    <!-- Appointment Selection -->
                    @if($appointments->count() > 0)
                    <div class="mb-3">
                        <label for="appointment_id" class="form-label">
                            <i class="bi bi-calendar-check me-1"></i>Related Appointment
                        </label>
                        <select name="appointment_id" id="appointment_id" class="form-select">
                            <option value="">Select appointment (optional)</option>
                            @foreach($appointments as $appointment)
                                <option value="{{ $appointment->id }}">
                                    {{ $appointment->appointment_date->format('M d, Y h:i A') }} - {{ $appointment->status }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Link this consultation to a scheduled appointment</small>
                    </div>
                    @endif

                    <!-- Consultation Notes -->
                    <div class="mb-3">
                        <label for="consultation_notes" class="form-label">
                            <i class="bi bi-journal-text me-1"></i>Consultation Notes
                        </label>
                        <textarea name="consultation_notes" id="consultation_notes" class="form-control" rows="5" 
                                  placeholder="Enter consultation notes, observations, and findings...">{{ old('consultation_notes') }}</textarea>
                        @error('consultation_notes')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Prescription -->
                    <div class="mb-3">
                        <label for="prescription" class="form-label">
                            <i class="bi bi-prescription me-1"></i>Prescription
                        </label>
                        <textarea name="prescription" id="prescription" class="form-control" rows="5" 
                                  placeholder="Enter prescribed medications and dosages...">{{ old('prescription') }}</textarea>
                        @error('prescription')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Medical Recommendations -->
                    <div class="mb-3">
                        <label for="medical_recommendations" class="form-label">
                            <i class="bi bi-lightbulb me-1"></i>Medical Recommendations
                        </label>
                        <textarea name="medical_recommendations" id="medical_recommendations" class="form-control" rows="4" 
                                  placeholder="Enter medical recommendations, lifestyle changes, and advice...">{{ old('medical_recommendations') }}</textarea>
                        @error('medical_recommendations')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Next Visit Information -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="next_visit_date" class="form-label">
                                <i class="bi bi-calendar-event me-1"></i>Next Visit Date
                            </label>
                            <input type="date" name="next_visit_date" id="next_visit_date" class="form-control" 
                                   value="{{ old('next_visit_date') }}" min="{{ date('Y-m-d') }}">
                            @error('next_visit_date')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="next_visit_notes" class="form-label">
                            <i class="bi bi-sticky me-1"></i>Next Visit Notes
                        </label>
                        <textarea name="next_visit_notes" id="next_visit_notes" class="form-control" rows="3" 
                                  placeholder="Enter notes for the next visit, follow-up instructions...">{{ old('next_visit_notes') }}</textarea>
                        @error('next_visit_notes')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="{{ route('doctor.consultations.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Save Consultation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
