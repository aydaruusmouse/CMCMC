@extends('layouts.app')

@section('title', 'Edit Package')

@section('content')
<div class="page-header">
    <h1>Edit Package</h1>
    <p class="text-muted">Update package information</p>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.packages.update', $package) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Package Name *</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $package->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $package->description) }}</textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="price" class="form-label">Price ($) *</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $package->price) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="type" class="form-label">Type (for Chronic packages)</label>
                    <select class="form-select" id="type" name="type">
                        <option value="">None</option>
                        <option value="Diabetes" {{ old('type', $package->type) == 'Diabetes' ? 'selected' : '' }}>Diabetes</option>
                        <option value="Hypertension" {{ old('type', $package->type) == 'Hypertension' ? 'selected' : '' }}>Hypertension</option>
                        <option value="Both" {{ old('type', $package->type) == 'Both' ? 'selected' : '' }}>Both</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Package</button>
            </div>
        </form>
    </div>
</div>
@endsection

