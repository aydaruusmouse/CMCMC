@extends('layouts.app')

@section('title', 'Record Health Data')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="bi bi-heart-pulse me-3"></i>Record Health Data</h1>
        <p class="text-muted">Submit your daily health information</p>
    </div>
    <a href="{{ route('patient.health-data.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back
    </a>
</div>

@if($activeBooking)
<div class="alert alert-info mb-4">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Active Package:</strong> {{ $activeBooking->package->name }}
    @if($activeBooking->package->type)
        <span class="badge bg-primary ms-2">{{ $activeBooking->package->type }}</span>
    @endif
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Health Data Entry Form</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('patient.health-data.store') }}">
            @csrf

            <!-- Date Field -->
            <div class="mb-3">
                <label for="recorded_date" class="form-label">
                    <i class="bi bi-calendar me-1"></i>Date *</label>
                <input type="date" 
                       class="form-control @error('recorded_date') is-invalid @enderror" 
                       id="recorded_date" 
                       name="recorded_date" 
                       value="{{ old('recorded_date', date('Y-m-d')) }}" 
                       required>
                @error('recorded_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @php
                $packageName = strtolower($activeBooking->package->name);
                $packageType = $activeBooking->package->type ?? '';
                $isMaternal = str_contains($packageName, 'maternal');
                $isPediatric = str_contains($packageName, 'pediatric');
                $isChronic = str_contains($packageName, 'chronic');
                // Check for diabetes in package name or type
                $isDiabetes = $isChronic && (
                    str_contains($packageName, 'diabetes') || 
                    $packageType === 'Diabetes' || 
                    $packageType === 'Both'
                );
                // Check for hypertension in package name or type
                $isHypertension = $isChronic && (
                    str_contains($packageName, 'hypertension') || 
                    $packageType === 'Hypertension' || 
                    $packageType === 'Both'
                );
            @endphp

            <!-- Chronic - Diabetes Fields -->
            @if($isDiabetes)
            <div class="card bg-light mb-3">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-droplet me-2"></i>Diabetes Monitoring</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label for="blood_sugar" class="form-label">Blood Sugar (mg/dL)</label>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('blood_sugar') is-invalid @enderror" 
                                   id="blood_sugar" 
                                   name="blood_sugar" 
                                   value="{{ old('blood_sugar') }}"
                                   placeholder="e.g., 120.5">
                            @error('blood_sugar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="insulin_intake" class="form-label">Insulin Intake</label>
                            <input type="text" 
                                   class="form-control @error('insulin_intake') is-invalid @enderror" 
                                   id="insulin_intake" 
                                   name="insulin_intake" 
                                   value="{{ old('insulin_intake') }}"
                                   placeholder="e.g., 10 units">
                            @error('insulin_intake')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-0">
                        <label for="diet_log" class="form-label">Diet Log</label>
                        <textarea class="form-control @error('diet_log') is-invalid @enderror" 
                                  id="diet_log" 
                                  name="diet_log" 
                                  rows="3"
                                  placeholder="Record your meals and snacks...">{{ old('diet_log') }}</textarea>
                        @error('diet_log')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            @endif

            <!-- Chronic - Hypertension Fields -->
            @if($isHypertension)
            <div class="card bg-light mb-3">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="bi bi-heart-pulse me-2"></i>Blood Pressure Monitoring</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-4 mb-3">
                            <label for="systolic_bp" class="form-label">Systolic BP (mmHg)</label>
                            <input type="number" 
                                   class="form-control @error('systolic_bp') is-invalid @enderror" 
                                   id="systolic_bp" 
                                   name="systolic_bp" 
                                   value="{{ old('systolic_bp') }}"
                                   placeholder="e.g., 120">
                            @error('systolic_bp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="diastolic_bp" class="form-label">Diastolic BP (mmHg)</label>
                            <input type="number" 
                                   class="form-control @error('diastolic_bp') is-invalid @enderror" 
                                   id="diastolic_bp" 
                                   name="diastolic_bp" 
                                   value="{{ old('diastolic_bp') }}"
                                   placeholder="e.g., 80">
                            @error('diastolic_bp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-0">
                        <label for="bp_symptoms" class="form-label">Symptoms</label>
                        <textarea class="form-control @error('bp_symptoms') is-invalid @enderror" 
                                  id="bp_symptoms" 
                                  name="bp_symptoms" 
                                  rows="3"
                                  placeholder="Describe any symptoms you're experiencing...">{{ old('bp_symptoms') }}</textarea>
                        @error('bp_symptoms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            @endif

            <!-- Maternal Fields -->
            @if($isMaternal)
            <div class="card bg-light mb-3">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-heart me-2"></i>Maternal Care</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('weight') is-invalid @enderror" 
                                   id="weight" 
                                   name="weight" 
                                   value="{{ old('weight') }}"
                                   placeholder="e.g., 65.5">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fetal_movements" class="form-label">Fetal Movements (count)</label>
                            <input type="number" 
                                   class="form-control @error('fetal_movements') is-invalid @enderror" 
                                   id="fetal_movements" 
                                   name="fetal_movements" 
                                   value="{{ old('fetal_movements') }}"
                                   placeholder="e.g., 10">
                            @error('fetal_movements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label for="systolic_bp_maternal" class="form-label">Systolic BP (mmHg)</label>
                            <input type="number" 
                                   class="form-control @error('systolic_bp') is-invalid @enderror" 
                                   id="systolic_bp_maternal" 
                                   name="systolic_bp" 
                                   value="{{ old('systolic_bp') }}"
                                   placeholder="e.g., 120">
                            @error('systolic_bp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="diastolic_bp_maternal" class="form-label">Diastolic BP (mmHg)</label>
                            <input type="number" 
                                   class="form-control @error('diastolic_bp') is-invalid @enderror" 
                                   id="diastolic_bp_maternal" 
                                   name="diastolic_bp" 
                                   value="{{ old('diastolic_bp') }}"
                                   placeholder="e.g., 80">
                            @error('diastolic_bp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-0">
                        <label for="maternal_symptoms" class="form-label">Symptoms</label>
                        <textarea class="form-control @error('maternal_symptoms') is-invalid @enderror" 
                                  id="maternal_symptoms" 
                                  name="maternal_symptoms" 
                                  rows="3"
                                  placeholder="Describe any symptoms or concerns...">{{ old('maternal_symptoms') }}</textarea>
                        @error('maternal_symptoms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            @endif

            <!-- Pediatric Fields -->
            @if($isPediatric)
            <div class="card bg-light mb-3">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0"><i class="bi bi-heart-fill me-2"></i>Pediatric Care</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('weight') is-invalid @enderror" 
                                   id="weight" 
                                   name="weight" 
                                   value="{{ old('weight') }}"
                                   placeholder="e.g., 15.5">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="temperature" class="form-label">Temperature (°C)</label>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('temperature') is-invalid @enderror" 
                                   id="temperature" 
                                   name="temperature" 
                                   value="{{ old('temperature') }}"
                                   placeholder="e.g., 37.5">
                            @error('temperature')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-0">
                        <label for="pediatric_symptoms" class="form-label">Symptoms</label>
                        <textarea class="form-control @error('pediatric_symptoms') is-invalid @enderror" 
                                  id="pediatric_symptoms" 
                                  name="pediatric_symptoms" 
                                  rows="3"
                                  placeholder="Describe any symptoms or concerns...">{{ old('pediatric_symptoms') }}</textarea>
                        @error('pediatric_symptoms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            @endif

            <!-- General Notes -->
            <div class="mb-4">
                <label for="notes" class="form-label">
                    <i class="bi bi-journal-text me-1"></i>Additional Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" 
                          name="notes" 
                          rows="3"
                          placeholder="Any additional information or concerns...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('patient.health-data.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Save Health Data
                </button>
            </div>
        </form>
    </div>
</div>
@else
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>No Active Booking:</strong> You need an active booking to record health data. Please contact your agent.
</div>
@endif
@endsection
